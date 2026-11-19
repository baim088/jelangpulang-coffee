<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderService
{
    public function __construct(
        protected StockService $stockService,
        protected CartService $cartService
    ) {}

    /**
     * Generate format nomor pesanan unik per hari: JP-YYYYMMDD-NNNN (BR-12).
     */
    public function generateOrderNumber(): string
    {
        $today = now()->format('Ymd');
        $prefix = "JP-{$today}-";

        // Ambil nomor urut terakhir hari ini
        $lastOrder = Order::where('order_no', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextSeq = 1;
        if ($lastOrder) {
            $parts = explode('-', $lastOrder->order_no);
            if (isset($parts[2])) {
                $nextSeq = intval($parts[2]) + 1;
            }
        }

        return $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Buat pesanan baru dari keranjang belanja.
     */
    public function createFromCart(array $validatedData, ?User $user = null): Order
    {
        $cartItems = $this->cartService->all();
        if (empty($cartItems)) {
            throw new Exception('Keranjang belanja masih kosong.');
        }

        $timeoutMinutes = (int) StoreSetting::get('payment_timeout_minutes', 30);

        return DB::transaction(function () use ($validatedData, $user, $cartItems, $timeoutMinutes) {
            $subtotal = $this->cartService->subtotal();
            $total = $this->cartService->total();

            $order = Order::create([
                'order_no' => $this->generateOrderNumber(),
                'user_id' => $user?->id,
                'customer_name' => $validatedData['customer_name'],
                'table_no' => $validatedData['table_no'] ?? null,
                'channel' => $validatedData['channel'] ?? 'self_order',
                'status' => 'menunggu_verifikasi',
                'payment_method' => $validatedData['payment_method'], // 'qris' atau 'pay_at_counter'
                'subtotal' => $subtotal,
                'total' => $total,
                'expired_at' => now()->addMinutes($timeoutMinutes),
            ]);

            // Simpan setiap item belanja dan add-on-nya
            foreach ($cartItems as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'parent_id' => null,
                    'product_name' => $item['product_name'],
                    'variant' => $item['variant'] ?? null,
                    'options' => $item['options'] ?? null,
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['unit_price'] * $item['qty'],
                    'note' => $item['note'] ?? null,
                ]);

                // Simpan add-ons sebagai child item jika ada
                if (!empty($item['add_ons'])) {
                    foreach ($item['add_ons'] as $addOn) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $addOn['product_id'],
                            'parent_id' => $orderItem->id,
                            'product_name' => $addOn['name'],
                            'variant' => 'all',
                            'qty' => $item['qty'], // ikut jumlah minuman induk
                            'unit_price' => $addOn['price'],
                            'subtotal' => $addOn['price'] * $item['qty'],
                            'note' => null,
                        ]);
                    }
                }
            }

            // Validasi ketersediaan stok sebelum checkout disetujui (BR-06)
            [$isAvailable, $missingItems] = $this->stockService->checkAvailability($order);
            if (!$isAvailable) {
                $names = implode(', ', array_column($missingItems, 'name'));
                throw new Exception("Maaf, stok bahan baku tidak mencukupi untuk: {$names}.");
            }

            // Kosongkan keranjang setelah order berhasil tersimpan
            $this->cartService->clear();

            return $order;
        });
    }

    /**
     * Simpan file bukti transfer pembayaran QRIS dari pelanggan (BR-16).
     */
    public function attachPaymentProof(Order $order, $file): void
    {
        if ($order->isDibatalkan()) {
            throw new Exception('Pesanan ini sudah dibatalkan dan tidak dapat menerima bukti bayar.');
        }

        // Simpan file ke storage public/payment_proofs
        $path = $file->store('payment_proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'status' => 'menunggu_verifikasi',
        ]);
    }
}

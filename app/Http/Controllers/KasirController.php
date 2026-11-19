<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Services\OrderService;
use App\Services\StockService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function __construct(
        protected StockService $stockService,
        protected OrderService $orderService
    ) {}

    /**
     * FR-C01 & FR-C02: Antrean Pesanan Kasir
     */
    public function antrean(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search');
        $dining = $request->query('dining', 'all'); // 'all', 'dine_in', 'takeaway'

        $query = Order::with(['items.product', 'items.addOns.product', 'user'])
            ->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($dining === 'dine_in') {
            $query->whereNotNull('table_no');
        } elseif ($dining === 'takeaway') {
            $query->whereNull('table_no');
        }

        $orders = $query->paginate(20)->withQueryString();

        // Metrics ribbon hari ini (FR-C01)
        $today = now()->startOfDay();
        $metrics = [
            'total_today' => Order::where('created_at', '>=', $today)->count(),
            'menunggu'    => Order::where('status', 'menunggu_verifikasi')->count(),
            'diproses'    => Order::where('status', 'diproses')->count(),
            'selesai'     => Order::where('status', 'selesai')->where('created_at', '>=', $today)->count(),
            'dibatalkan'  => Order::where('status', 'dibatalkan')->where('created_at', '>=', $today)->count(),
        ];

        return view('kasir.antrean', compact('orders', 'metrics', 'status', 'search', 'dining'));
    }

    /**
     * FR-C03: Kasir Verifikasi & Terima Pesanan Masuk
     * Memotong stok, mencatat mutasi OUT, ubah status ke 'diproses' dalam 1 DB transaction.
     */
    public function verifikasi(Request $request, $order_no)
    {
        $order = Order::where('order_no', $order_no)->firstOrFail();

        if ($order->status !== 'menunggu_verifikasi') {
            return back()->with('error', "Pesanan {$order->order_no} tidak dapat diverifikasi karena berstatus '{$order->status}'.");
        }

        // Cek ketersediaan stok terlebih dahulu (BR-01, BR-02)
        [$isAvailable, $missingItems] = $this->stockService->checkAvailability($order);
        if (!$isAvailable) {
            $missingNames = collect($missingItems)->map(fn($m) => "{$m['name']} (butuh {$m['required']}{$m['unit']}, ada {$m['available']})")->implode(', ');
            return back()->with('error', "Gagal verifikasi! Stok tidak mencukupi: {$missingNames}.");
        }

        try {
            DB::transaction(function () use ($order) {
                // Potong stok bahan baku & kemasan
                $this->stockService->deductForOrder($order, auth()->user());

                // Update status pesanan
                $order->update([
                    'status' => 'diproses',
                    'processed_by' => auth()->id(),
                    'paid_at' => $order->paid_at ?: now(),
                ]);
            });

            return back()->with('success', "Pesanan {$order->order_no} berhasil diverifikasi. Stok telah dipotong dan pesanan masuk antrean barista.");
        } catch (Exception $e) {
            return back()->with('error', "Gagal memproses pesanan: {$e->getMessage()}");
        }
    }

    /**
     * FR-C02: Batalkan pesanan dengan alasan wajib
     */
    public function batalkan(Request $request, $order_no)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ], [
            'cancel_reason.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $order = Order::where('order_no', $order_no)->firstOrFail();

        if (in_array($order->status, ['selesai', 'dibatalkan'], true)) {
            return back()->with('error', "Pesanan berstatus '{$order->status}' tidak dapat dibatalkan.");
        }

        try {
            DB::transaction(function () use ($order, $request) {
                // Jika sebelumnya sudah diproses (dan stok sudah dipotong), kembalikan stok
                if ($order->status === 'diproses') {
                    $this->stockService->returnStockForVoidOrder($order, auth()->user());
                }

                $order->update([
                    'status' => 'dibatalkan',
                    'cancel_reason' => $request->cancel_reason,
                    'processed_by' => auth()->id(),
                ]);
            });

            return back()->with('success', "Pesanan {$order->order_no} berhasil dibatalkan.");
        } catch (Exception $e) {
            return back()->with('error', "Gagal membatalkan pesanan: {$e->getMessage()}");
        }
    }

    /**
     * FR-C07: Ubah status pesanan diproses -> selesai
     */
    public function selesai(Request $request, $order_no)
    {
        $order = Order::where('order_no', $order_no)->firstOrFail();

        if ($order->status !== 'diproses') {
            return back()->with('error', "Hanya pesanan berstatus 'diproses' yang dapat diselesaikan.");
        }

        $order->update([
            'status' => 'selesai',
        ]);

        return back()->with('success', "Pesanan {$order->order_no} telah selesai dan siap diserahkan.");
    }

    /**
     * FR-C04: Halaman POS Walk-in Kasir
     */
    public function pos(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->where('slug', '!=', 'tambahan')
            ->orderBy('sort_order')
            ->with(['products' => function ($q) {
                $q->where('is_active', true)->with('addOns');
            }])
            ->get();

        $addOns = Product::where('is_active', true)
            ->whereHas('category', fn($c) => $c->where('slug', 'tambahan'))
            ->get();

        return view('kasir.pos', compact('categories', 'addOns'));
    }

    /**
     * FR-C04, FR-C05, FR-C06: Checkout Transaksi POS Walk-in
     */
    public function posCheckout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.variant' => 'nullable|string',
            'payment_method' => 'required|in:cash,qris',
            'customer_name' => 'nullable|string|max:100',
            'table_no' => 'nullable|string|max:20',
            'cash_received' => 'nullable|numeric|min:0',
        ]);

        $customerName = $request->customer_name ?: 'Pelanggan Walk-in';
        $tableNo = $request->table_no ?: null;
        $paymentMethod = $request->payment_method;

        // Hitung total dan susun item pesanan
        $orderItemsData = [];
        $subtotal = 0;

        foreach ($request->items as $cartItem) {
            $product = Product::findOrFail($cartItem['product_id']);
            $qty = (int) $cartItem['qty'];
            $variant = $cartItem['variant'] ?? null;
            $options = $cartItem['options'] ?? null;
            $note = $cartItem['note'] ?? null;

            $itemSubtotal = $product->price * $qty;
            $subtotal += $itemSubtotal;

            $parentItemIndex = count($orderItemsData);
            $orderItemsData[] = [
                'product_id' => $product->id,
                'parent_id' => null,
                'product_name' => $product->name,
                'variant' => $variant,
                'options' => $options,
                'qty' => $qty,
                'unit_price' => $product->price,
                'subtotal' => $itemSubtotal,
                'note' => $note,
                'add_ons' => $cartItem['add_ons'] ?? [],
            ];
        }

        $total = $subtotal;
        $cashReceived = null;
        $changeAmount = null;

        if ($paymentMethod === 'cash') {
            $cashReceived = (int) $request->cash_received;
            if ($cashReceived < $total) {
                return back()->withInput()->with('error', "Nominal uang diterima (Rp " . number_format($cashReceived, 0, ',', '.') . ") kurang dari total belanja (Rp " . number_format($total, 0, ',', '.') . ").");
            }
            $changeAmount = $cashReceived - $total;
        }

        try {
            $order = DB::transaction(function () use (
                $customerName, $tableNo, $paymentMethod, $subtotal, $total,
                $cashReceived, $changeAmount, $orderItemsData
            ) {
                $orderNo = $this->orderService->generateOrderNumber();

                $order = Order::create([
                    'order_no' => $orderNo,
                    'user_id' => null,
                    'customer_name' => $customerName,
                    'table_no' => $tableNo,
                    'channel' => 'walk_in',
                    'status' => 'diproses',
                    'payment_method' => $paymentMethod,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'cash_received' => $cashReceived,
                    'change_amount' => $changeAmount,
                    'paid_at' => now(),
                    'processed_by' => auth()->id(),
                ]);

                // Simpan item dan add-on
                foreach ($orderItemsData as $itemData) {
                    $addOns = $itemData['add_ons'];
                    unset($itemData['add_ons']);

                    $orderItem = $order->items()->create($itemData);

                    if (!empty($addOns)) {
                        foreach ($addOns as $addOnId) {
                            $addOnProduct = Product::find($addOnId);
                            if ($addOnProduct) {
                                $addOnSubtotal = $addOnProduct->price * $orderItem->qty;
                                $order->items()->create([
                                    'product_id' => $addOnProduct->id,
                                    'parent_id' => $orderItem->id,
                                    'product_name' => $addOnProduct->name,
                                    'variant' => null,
                                    'options' => null,
                                    'qty' => $orderItem->qty,
                                    'unit_price' => $addOnProduct->price,
                                    'subtotal' => $addOnSubtotal,
                                    'note' => null,
                                ]);
                            }
                        }
                    }
                }

                // Validasi dan potong stok langsung untuk transaksi kasir walk-in (BR-01, BR-02)
                $this->stockService->deductForOrder($order, auth()->user());

                return $order;
            });

            return redirect()->route('kasir.pos')->with('success_order', $order->order_no)->with('success', "Transaksi {$order->order_no} berhasil! Kembalian: Rp " . number_format($order->change_amount ?? 0, 0, ',', '.'));
        } catch (Exception $e) {
            return back()->withInput()->with('error', "Gagal memproses transaksi: {$e->getMessage()}");
        }
    }

    /**
     * FR-C09: Rekap Shift Kasir
     */
    public function rekap(Request $request)
    {
        $today = now()->startOfDay();

        // Ambil transaksi lunas hari ini
        $orders = Order::where('created_at', '>=', $today)
            ->whereIn('status', ['diproses', 'selesai'])
            ->with(['items'])
            ->latest()
            ->get();

        $totalOmzet = $orders->sum('total');
        $totalTransaksi = $orders->count();
        $totalCash = $orders->where('payment_method', 'cash')->sum('total');
        $totalQris = $orders->where('payment_method', 'qris')->sum('total');

        $cancelledOrders = Order::where('created_at', '>=', $today)
            ->where('status', 'dibatalkan')
            ->count();

        return view('kasir.rekap', compact(
            'orders', 'totalOmzet', 'totalTransaksi', 'totalCash', 'totalQris', 'cancelledOrders'
        ));
    }

    /**
     * FR-C08: Cetak Struk Thermal 58mm
     */
    public function struk($order_no)
    {
        $order = Order::where('order_no', $order_no)
            ->with(['items.product', 'items.addOns.product', 'processor'])
            ->firstOrFail();

        $store = StoreSetting::first();

        return view('kasir.struk', compact('order', 'store'));
    }
}

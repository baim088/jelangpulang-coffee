<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService
    ) {}

    public function index()
    {
        // Kasir dan Admin tidak diperkenankan melakukan self-order pelanggan
        if (auth()->check() && (auth()->user()->isKasir() || auth()->user()->isAdmin())) {
            return redirect()->route('kasir.pos')->with('error', 'Akun kasir/staf tidak dapat membuat self-order pelanggan. Silakan gunakan sistem POS Kasir.');
        }

        $items = $this->cartService->all();
        if (empty($items)) {
            return redirect()->route('catalog.index')->with('error', 'Keranjang Anda masih kosong. Silakan pilih menu terlebih dahulu.');
        }

        $subtotal = $this->cartService->subtotal();
        $total = $this->cartService->total();

        return view('customer.checkout', compact('items', 'subtotal', 'total'));
    }

    public function store(Request $request)
    {
        // Kasir dan Admin tidak diperkenankan melakukan self-order pelanggan
        if (auth()->check() && (auth()->user()->isKasir() || auth()->user()->isAdmin())) {
            return redirect()->route('kasir.pos')->with('error', 'Akun kasir/staf tidak dapat membuat self-order pelanggan. Silakan gunakan sistem POS Kasir.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:60',
            'order_type' => 'required|in:dinein,takeaway',
            'table_no' => 'nullable|string|max:20',
            'payment_method' => 'required|in:qris,pay_at_counter',
        ]);

        // Jika dine-in tapi tidak isi meja, tandai umum
        $tableNo = $validated['order_type'] === 'takeaway' ? 'Takeaway' : ($validated['table_no'] ?: 'Meja Langsung');

        try {
            $order = $this->orderService->createFromCart([
                'customer_name' => $validated['customer_name'],
                'table_no' => $tableNo,
                'channel' => 'self_order',
                'payment_method' => $validated['payment_method'],
            ], auth()->user());

            if ($order->payment_method === 'qris') {
                return redirect()->route('order.payment', $order->order_no)
                    ->with('success', 'Pesanan dibuat. Silakan selesaikan pembayaran QRIS.');
            }

            return redirect()->route('order.status', $order->order_no)
                ->with('success', 'Pesanan dibuat. Silakan lakukan pembayaran di kasir.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}

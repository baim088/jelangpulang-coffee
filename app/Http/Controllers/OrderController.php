<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StoreSetting;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function payment(string $order_no)
    {
        $order = Order::where('order_no', $order_no)
            ->with(['items.product', 'items.addOns.product'])
            ->firstOrFail();

        // Jika sudah diproses atau selesai, langsung ke status
        if ($order->isDiproses() || $order->isSelesai()) {
            return redirect()->route('order.status', $order->order_no);
        }

        $qrisImage = StoreSetting::get('qris_image', 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=00020101021226670016ID.CO.SHOPEE.WWW0118936009180000000000021500000000000000005204581253033605802ID5912JELANGPULANG6007JAKARTA61051234062070703A016304ABCD');

        return view('customer.payment', compact('order', 'qrisImage'));
    }

    public function uploadProof(Request $request, string $order_no)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // maks 2 MB (BR-16)
        ]);

        $order = Order::where('order_no', $order_no)->firstOrFail();

        try {
            $this->orderService->attachPaymentProof($order, $request->file('payment_proof'));

            return redirect()->route('order.status', $order->order_no)
                ->with('success', 'Bukti bayar berhasil diunggah. Kasir sedang memverifikasi pesanan Anda.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function status(string $order_no)
    {
        $order = Order::where('order_no', $order_no)
            ->with(['items.product', 'items.addOns.product'])
            ->firstOrFail();

        return view('customer.status', compact('order'));
    }

    public function apiStatus(string $order_no)
    {
        $order = Order::where('order_no', $order_no)->first();
        if (!$order) {
            return response()->json(['error' => 'Pesanan tidak ditemukan'], 404);
        }

        $labels = [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diracik',
            'selesai' => 'Siap Diambil / Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        return response()->json([
            'order_no' => $order->order_no,
            'status' => $order->status,
            'status_label' => $labels[$order->status] ?? $order->status,
            'has_proof' => !empty($order->payment_proof),
            'cancel_reason' => $order->cancel_reason,
            'is_expired' => $order->expired_at && now()->greaterThan($order->expired_at),
        ]);
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('customer.history', compact('orders'));
    }
}

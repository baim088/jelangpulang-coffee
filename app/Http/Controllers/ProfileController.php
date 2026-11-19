<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan Dashboard & Profil Pelanggan
     */
    public function index()
    {
        $user = auth()->user();

        // Statistik belanja pelanggan
        $totalSpending = Order::where('user_id', $user->id)
            ->whereIn('status', ['diproses', 'selesai'])
            ->sum('total');

        $totalOrders = Order::where('user_id', $user->id)->count();

        $completedOrders = Order::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->count();

        // Pesanan yang sedang aktif (menunggu verifikasi / sedang diracik)
        $activeOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['menunggu_verifikasi', 'diproses'])
            ->latest()
            ->get();

        // Menu yang paling sering dipesan
        $favoriteItem = OrderItem::whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->whereIn('status', ['diproses', 'selesai']);
            })
            ->whereNull('parent_id')
            ->select('product_name', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->first();

        // Riwayat pesanan singkat (3 pesanan terakhir)
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['items'])
            ->latest()
            ->take(3)
            ->get();

        return view('customer.profile', compact(
            'user',
            'totalSpending',
            'totalOrders',
            'completedOrders',
            'activeOrders',
            'favoriteItem',
            'recentOrders'
        ));
    }

    /**
     * Update Data Profil (Nama, Email, WhatsApp)
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique'   => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Ganti Kata Sandi
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:6|confirmed',
        ], [
            'current_password.required'         => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.required'                 => 'Kata sandi baru wajib diisi.',
            'password.min'                      => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed'                => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui.');
    }
}

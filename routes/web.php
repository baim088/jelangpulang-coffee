<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — JelangPulang
|--------------------------------------------------------------------------
*/

// Modul A: Publik & Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Modul B: Katalog & Self-Order User
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');

Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/tambah', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/hapus', [CartController::class, 'remove'])->name('remove');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
});

Route::prefix('pesanan')->name('order.')->group(function () {
    Route::get('/riwayat', [OrderController::class, 'history'])->name('history')->middleware('auth');
    Route::get('/{order_no}', [OrderController::class, 'status'])->name('status');
    Route::get('/{order_no}/bayar', [OrderController::class, 'payment'])->name('payment');
    Route::post('/{order_no}/bayar', [OrderController::class, 'uploadProof'])->name('upload_proof');
});

// Endpoint Polling Status Realtime (FR-B08)
Route::get('/api/pesanan/{order_no}/status', [OrderController::class, 'apiStatus'])->name('order.api_status');

// Autentikasi Pengguna
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Modul C: Kasir Operasional (FR-C01 s/d FR-C10)
Route::prefix('kasir')->name('kasir.')->middleware(['auth', 'role:kasir,admin'])->group(function () {
    Route::get('/antrean', [KasirController::class, 'antrean'])->name('antrean');
    Route::post('/antrean/{order_no}/verifikasi', [KasirController::class, 'verifikasi'])->name('antrean.verifikasi');
    Route::post('/antrean/{order_no}/batalkan', [KasirController::class, 'batalkan'])->name('antrean.batalkan');
    Route::post('/antrean/{order_no}/selesai', [KasirController::class, 'selesai'])->name('antrean.selesai');

    Route::get('/pos', [KasirController::class, 'pos'])->name('pos');
    Route::post('/pos/checkout', [KasirController::class, 'posCheckout'])->name('pos.checkout');

    Route::get('/rekap', [KasirController::class, 'rekap'])->name('rekap');
    Route::get('/pesanan/{order_no}/struk', [KasirController::class, 'struk'])->name('struk');
});

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_deduct_stock_with_oatmilk_substitution(): void
    {
        // 1. Seed database awal
        $this->seed(\Database\Seeders\JelangPulangSeeder::class);

        $admin = User::where('role', 'admin')->first();
        $kopiJelangPulang = Product::where('slug', 'kopi-jelang-pulang')->first();
        $oatmilk = Product::where('slug', 'oatmilk')->first();

        $susuSapi = StockItem::where('name', 'Susu Sapi Segar')->first();
        $susuOat = StockItem::where('name', 'Susu Oat')->first();
        $bijiKopi = StockItem::where('name', 'Biji Kopi House Blend')->first();

        $initialSusuSapi = $susuSapi->current_stock;
        $initialSusuOat = $susuOat->current_stock;
        $initialBijiKopi = $bijiKopi->current_stock;

        // 2. Buat pesanan: 1x Kopi Jelang Pulang (Ice) + Oatmilk
        $order = Order::create([
            'order_no' => 'JP-TEST-0001',
            'customer_name' => 'Dimas Test',
            'table_no' => 'Meja 05',
            'channel' => 'self_order',
            'status' => 'menunggu_verifikasi',
            'payment_method' => 'qris',
            'subtotal' => 29000,
            'total' => 29000,
        ]);

        $parentItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $kopiJelangPulang->id,
            'product_name' => $kopiJelangPulang->name,
            'variant' => 'ice',
            'qty' => 1,
            'unit_price' => 24000,
            'subtotal' => 24000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $oatmilk->id,
            'parent_id' => $parentItem->id,
            'product_name' => $oatmilk->name,
            'variant' => 'all',
            'qty' => 1,
            'unit_price' => 7000,
            'subtotal' => 7000,
        ]);

        // 3. Jalankan StockService
        $service = new StockService();
        $service->deductForOrder($order, $admin);

        // 4. Assert:
        // - Susu Sapi Segar TIDAK boleh berkurang (karena digantikan oleh Oatmilk!)
        // - Susu Oat HARUS berkurang 120 ml
        // - Biji Kopi HARUS berkurang 18 gram
        $susuSapi->refresh();
        $susuOat->refresh();
        $bijiKopi->refresh();

        $this->assertEquals($initialSusuSapi, $susuSapi->current_stock, 'Susu Sapi tidak boleh berkurang saat diganti Oatmilk');
        $this->assertEquals($initialSusuOat - 120, $susuOat->current_stock, 'Susu Oat harus berkurang tepat 120 ml');
        $this->assertEquals($initialBijiKopi - 18, $bijiKopi->current_stock, 'Biji Kopi harus berkurang 18 gram');
    }

    public function test_deduct_stock_normal_racikan_and_produk_jadi(): void
    {
        $this->seed(\Database\Seeders\JelangPulangSeeder::class);

        $admin = User::where('role', 'admin')->first();
        $caffeLatte = Product::where('slug', 'caffe-latte')->first();
        $croissant = Product::where('slug', 'butter-croissant')->first();

        $susuSapi = StockItem::where('name', 'Susu Sapi Segar')->first();
        $bijiKopi = StockItem::where('name', 'Biji Kopi House Blend')->first();
        $croissantStock = StockItem::where('name', 'Butter Croissant')->first();

        $initSusuSapi = $susuSapi->current_stock;
        $initBijiKopi = $bijiKopi->current_stock;
        $initCroissant = $croissantStock->current_stock;

        $order = Order::create([
            'order_no' => 'JP-TEST-0002',
            'customer_name' => 'Sari Pelanggan',
            'channel' => 'walk_in',
            'status' => 'diproses',
            'payment_method' => 'cash',
            'subtotal' => 39000,
            'total' => 39000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $caffeLatte->id,
            'product_name' => $caffeLatte->name,
            'variant' => 'ice',
            'qty' => 1,
            'unit_price' => 21000,
            'subtotal' => 21000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $croissant->id,
            'product_name' => $croissant->name,
            'qty' => 2,
            'unit_price' => 18000,
            'subtotal' => 36000,
        ]);

        $service = new StockService();
        $service->deductForOrder($order, $admin);

        $susuSapi->refresh();
        $bijiKopi->refresh();
        $croissantStock->refresh();

        // Caffe Latte Ice resep: 18g kopi, 140ml susu sapi
        // Croissant produk jadi: 2x 1:1 unit stok
        $this->assertEquals($initBijiKopi - 18, $bijiKopi->current_stock);
        $this->assertEquals($initSusuSapi - 140, $susuSapi->current_stock);
        $this->assertEquals($initCroissant - 2, $croissantStock->current_stock);
    }
}

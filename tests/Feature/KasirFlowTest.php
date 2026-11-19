<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use Database\Seeders\JelangPulangSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JelangPulangSeeder::class);
    }

    public function test_user_biasa_dilarang_mengakses_halaman_kasir()
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get(route('kasir.antrean'));
        $response->assertStatus(403);
    }

    public function test_kasir_dapat_mengakses_semua_menu_kasir()
    {
        $kasir = User::where('role', 'kasir')->first();

        $this->actingAs($kasir)->get(route('kasir.antrean'))->assertOk();
        $this->actingAs($kasir)->get(route('kasir.pos'))->assertOk();
        $this->actingAs($kasir)->get(route('kasir.rekap'))->assertOk();
    }

    public function test_kasir_dapat_memverifikasi_pesanan_dan_stok_terpotong()
    {
        $kasir = User::where('role', 'kasir')->first();
        $product = Product::where('slug', 'kopi-jelang-pulang')->first();

        // Cari stok bahan baku kopi susu senja (Biji Kopi House Blend)
        $bijiKopi = StockItem::where('name', 'Biji Kopi House Blend')->first();
        $initialStock = $bijiKopi->current_stock;

        // Buat order menunggu verifikasi
        $order = Order::create([
            'order_no' => 'JP-TEST-0001',
            'customer_name' => 'Budi Santoso',
            'table_no' => '05',
            'channel' => 'self_order',
            'status' => 'menunggu_verifikasi',
            'payment_method' => 'qris',
            'subtotal' => 24000,
            'total' => 24000,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'variant' => 'ice',
            'qty' => 1,
            'unit_price' => 24000,
            'subtotal' => 24000,
        ]);

        // Kasir verifikasi pesanan
        $response = $this->actingAs($kasir)->post(route('kasir.antrean.verifikasi', $order->order_no));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Pastikan status order berubah ke diproses
        $order->refresh();
        $this->assertEquals('diproses', $order->status);
        $this->assertEquals($kasir->id, $order->processed_by);
        $this->assertNotNull($order->paid_at);

        // Pastikan stok terpotong (18g biji kopi per porsi)
        $bijiKopi->refresh();
        $this->assertEquals($initialStock - 18, $bijiKopi->current_stock);

        // Pastikan jurnal mutasi OUT tercatat
        $this->assertDatabaseHas('stock_movements', [
            'stock_item_id' => $bijiKopi->id,
            'direction' => 'OUT',
            'source_type' => 'order',
            'source_id' => $order->id,
            'qty' => 18,
        ]);
    }

    public function test_kasir_dapat_membatalkan_pesanan_dan_stok_dikembalikan_jika_sudah_diproses()
    {
        $kasir = User::where('role', 'kasir')->first();
        $product = Product::where('slug', 'kopi-jelang-pulang')->first();
        $bijiKopi = StockItem::where('name', 'Biji Kopi House Blend')->first();
        $initialStock = $bijiKopi->current_stock;

        $order = Order::create([
            'order_no' => 'JP-TEST-0002',
            'customer_name' => 'Ani Lestari',
            'table_no' => '03',
            'channel' => 'self_order',
            'status' => 'menunggu_verifikasi',
            'payment_method' => 'qris',
            'subtotal' => 24000,
            'total' => 24000,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'variant' => 'ice',
            'qty' => 1,
            'unit_price' => 24000,
            'subtotal' => 24000,
        ]);

        // Verifikasi dulu agar stok terpotong
        $this->actingAs($kasir)->post(route('kasir.antrean.verifikasi', $order->order_no));
        $bijiKopi->refresh();
        $this->assertEquals($initialStock - 18, $bijiKopi->current_stock);

        // Kemudian batalkan pesanan
        $response = $this->actingAs($kasir)->post(route('kasir.antrean.batalkan', $order->order_no), [
            'cancel_reason' => 'Pelanggan salah transfer bank',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('dibatalkan', $order->status);
        $this->assertEquals('Pelanggan salah transfer bank', $order->cancel_reason);

        // Stok harus kembali ke semula (void restoration)
        $bijiKopi->refresh();
        $this->assertEquals($initialStock, $bijiKopi->current_stock);

        // Pastikan tercatat mutasi IN tipe void
        $this->assertDatabaseHas('stock_movements', [
            'stock_item_id' => $bijiKopi->id,
            'direction' => 'IN',
            'source_type' => 'void',
            'source_id' => $order->id,
            'qty' => 18,
        ]);
    }

    public function test_kasir_dapat_menyelesaikan_pesanan()
    {
        $kasir = User::where('role', 'kasir')->first();

        $order = Order::create([
            'order_no' => 'JP-TEST-0003',
            'customer_name' => 'Rina',
            'table_no' => '01',
            'channel' => 'self_order',
            'status' => 'diproses',
            'payment_method' => 'qris',
            'subtotal' => 24000,
            'total' => 24000,
        ]);

        $response = $this->actingAs($kasir)->post(route('kasir.antrean.selesai', $order->order_no));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('selesai', $order->status);
    }

    public function test_transaksi_pos_walk_in_kasir_dengan_bayar_tunai()
    {
        $kasir = User::where('role', 'kasir')->first();
        $product = Product::where('slug', 'kopi-jelang-pulang')->first();
        $bijiKopi = StockItem::where('name', 'Biji Kopi House Blend')->first();
        $initialStock = $bijiKopi->current_stock;

        $response = $this->actingAs($kasir)->post(route('kasir.pos.checkout'), [
            'customer_name' => 'Dimas',
            'table_no' => '07',
            'payment_method' => 'cash',
            'cash_received' => 50000,
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => 2,
                    'variant' => 'ice',
                    'note' => 'Gula sedikit',
                ]
            ]
        ]);

        $response->assertRedirect(route('kasir.pos'));
        $response->assertSessionHas('success');

        // Pastikan order tersimpan di DB
        $order = Order::where('customer_name', 'Dimas')->first();
        $this->assertNotNull($order);
        $this->assertEquals('walk_in', $order->channel);
        $this->assertEquals('cash', $order->payment_method);
        $this->assertEquals(44000, $order->total); // 22000 x 2
        $this->assertEquals(50000, $order->cash_received);
        $this->assertEquals(6000, $order->change_amount); // 50000 - 44000
        $this->assertEquals('diproses', $order->status);

        // Pastikan stok terpotong (2 x 18g = 36g)
        $bijiKopi->refresh();
        $this->assertEquals($initialStock - 36, $bijiKopi->current_stock);
    }

    public function test_struk_thermal_dapat_dibuka()
    {
        $kasir = User::where('role', 'kasir')->first();
        $product = Product::first();

        $order = Order::create([
            'order_no' => 'JP-TEST-0004',
            'customer_name' => 'Citra',
            'table_no' => '02',
            'channel' => 'walk_in',
            'status' => 'diproses',
            'payment_method' => 'cash',
            'subtotal' => 24000,
            'total' => 24000,
            'cash_received' => 50000,
            'change_amount' => 26000,
            'processed_by' => $kasir->id,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'qty' => 1,
            'unit_price' => 24000,
            'subtotal' => 24000,
        ]);

        $response = $this->actingAs($kasir)->get(route('kasir.struk', $order->order_no));
        $response->assertOk();
        $response->assertSee('JELANGPULANG');
        $response->assertSee('JP-TEST-0004');
        $response->assertSee('Kembalian:');
    }
}

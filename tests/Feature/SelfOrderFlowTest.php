<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockItem;
use Database\Seeders\JelangPulangSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SelfOrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_self_order_qris_flow(): void
    {
        // 1. Seed database
        $this->seed(JelangPulangSeeder::class);
        Storage::fake('public');

        // 2. Akses Landing Page
        $resHome = $this->get('/');
        $resHome->assertStatus(200);
        $resHome->assertSee('JELANGPULANG');
        $resHome->assertSee('SATU GELAS SEBELUM ARAH PULANG');

        // 3. Akses Katalog
        $resCatalog = $this->get('/katalog');
        $resCatalog->assertStatus(200);
        $resCatalog->assertSee('Kopi Jelang Pulang');

        // 4. Tambah Item ke Keranjang: Kopi Jelang Pulang (Ice + Oatmilk)
        $kopi = Product::where('slug', 'kopi-jelang-pulang')->first();
        $oatmilk = Product::where('slug', 'oatmilk')->first();

        $resAddToCart = $this->post('/keranjang/tambah', [
            'product_id' => $kopi->id,
            'variant' => 'ice',
            'sweetness' => 'normal',
            'ice_level' => 'normal_ice',
            'add_ons' => [$oatmilk->id],
            'qty' => 1,
            'note' => 'Kurang manis dikit',
        ]);
        $resAddToCart->assertRedirect(route('cart.index'));

        // 5. Cek Isi Keranjang
        // Harga: Rp 22.000 (dasar) + Rp 2.000 (ice delta) + Rp 7.000 (oatmilk) = Rp 31.000
        $resCart = $this->get('/keranjang');
        $resCart->assertStatus(200);
        $resCart->assertSee('Kopi Jelang Pulang');
        $resCart->assertSee('Oatmilk');
        $resCart->assertSee('31.000');

        // 6. Akses Halaman Checkout
        $resCheckout = $this->get('/checkout');
        $resCheckout->assertStatus(200);
        $resCheckout->assertSee('Konfirmasi');

        // 7. Submit Checkout
        $resStoreOrder = $this->post('/checkout', [
            'customer_name' => 'Dimas Pengunjung',
            'order_type' => 'dinein',
            'table_no' => 'Meja 07',
            'payment_method' => 'qris',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals('Dimas Pengunjung', $order->customer_name);
        $this->assertEquals('Meja 07', $order->table_no);
        $this->assertEquals('menunggu_verifikasi', $order->status);
        $this->assertEquals(31000, $order->total);
        $resStoreOrder->assertRedirect(route('order.payment', $order->order_no));

        // 8. Akses Halaman Pembayaran QRIS
        $resPayment = $this->get(route('order.payment', $order->order_no));
        $resPayment->assertStatus(200);
        $resPayment->assertSee($order->order_no);
        $resPayment->assertSee('31.000');

        // 9. Upload Bukti Transfer QRIS (Maks 2 MB)
        $fakeImage = UploadedFile::fake()->image('bukti_shopeepay.jpg', 600, 800);
        $resUpload = $this->post(route('order.upload_proof', $order->order_no), [
            'payment_proof' => $fakeImage,
        ]);
        $resUpload->assertRedirect(route('order.status', $order->order_no));

        $order->refresh();
        $this->assertNotNull($order->payment_proof);
        Storage::disk('public')->assertExists($order->payment_proof);

        // 10. Cek Halaman Status & Endpoint Polling API (FR-B08)
        $resStatus = $this->get(route('order.status', $order->order_no));
        $resStatus->assertStatus(200);
        $resStatus->assertSee($order->order_no);

        $resApi = $this->get(route('order.api_status', $order->order_no));
        $resApi->assertStatus(200);
        $resApi->assertJson([
            'order_no' => $order->order_no,
            'status' => 'menunggu_verifikasi',
            'status_label' => 'Menunggu Verifikasi',
            'has_proof' => true,
        ]);
    }
}

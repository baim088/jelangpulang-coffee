<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\JelangPulangSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirRoleRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JelangPulangSeeder::class);
    }

    public function test_kasir_dilarang_mengakses_checkout_self_order(): void
    {
        $kasir = User::where('role', 'kasir')->first();

        $response = $this->actingAs($kasir)->get('/checkout');
        $response->assertRedirect(route('kasir.pos'));
        $response->assertSessionHas('error');
    }

    public function test_kasir_dilarang_submit_pesanan_self_order(): void
    {
        $kasir = User::where('role', 'kasir')->first();

        $response = $this->actingAs($kasir)->post('/checkout', [
            'customer_name'  => 'Kasir Mencoba Pesan',
            'order_type'     => 'dinein',
            'table_no'       => '01',
            'payment_method' => 'qris',
        ]);

        $response->assertRedirect(route('kasir.pos'));
        $response->assertSessionHas('error');
    }

    public function test_kasir_dilarang_mengakses_riwayat_pesanan_pelanggan(): void
    {
        $kasir = User::where('role', 'kasir')->first();

        $response = $this->actingAs($kasir)->get('/pesanan/riwayat');
        $response->assertRedirect(route('kasir.antrean'));
        $response->assertSessionHas('error');
    }

    public function test_kasir_dilarang_mengakses_dashboard_profil_pelanggan(): void
    {
        $kasir = User::where('role', 'kasir')->first();

        $response = $this->actingAs($kasir)->get('/profil');
        $response->assertRedirect(route('kasir.antrean'));
        $response->assertSessionHas('error');
    }

    public function test_pelanggan_tetap_dapat_mengakses_riwayat_dan_profil(): void
    {
        $user = User::where('role', 'user')->first();

        $this->actingAs($user)->get('/pesanan/riwayat')->assertOk();
        $this->actingAs($user)->get('/profil')->assertOk();
    }

    public function test_kasir_dapat_memperbarui_profil_dan_ganti_kata_sandi(): void
    {
        $kasir = User::where('role', 'kasir')->first();

        // 1. Kasir update profil
        $responseProfile = $this->actingAs($kasir)->put('/profil', [
            'name'  => 'Barista Senior',
            'email' => 'barista.senior@jelangpulang.test',
            'phone' => '081298765432',
        ]);
        $responseProfile->assertRedirect();
        $responseProfile->assertSessionHas('success');

        $kasir->refresh();
        $this->assertEquals('Barista Senior', $kasir->name);
        $this->assertEquals('barista.senior@jelangpulang.test', $kasir->email);

        // 2. Kasir ganti password
        $responsePassword = $this->actingAs($kasir)->put('/profil/password', [
            'current_password'      => 'password',
            'password'              => 'kasirbaru123',
            'password_confirmation' => 'kasirbaru123',
        ]);
        $responsePassword->assertRedirect();
        $responsePassword->assertSessionHas('success');
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('kasirbaru123', $kasir->fresh()->password));
    }

    public function test_antrean_kasir_menampilkan_badge_metrik_dan_modal_profil(): void
    {
        $kasir = User::where('role', 'kasir')->first();

        $response = $this->actingAs($kasir)->get(route('kasir.antrean'));
        $response->assertOk();
        $response->assertSee('Operasional Meja & POS', false);
        $response->assertSee('Antrean Pesanan Masuk');
        $response->assertSee('Menunggu');
        $response->assertSee('Diproses');
        $response->assertSee('Selesai Hari Ini');
        $response->assertSee('Dibatalkan');
        $response->assertSee('Edit Profil');
        $response->assertSee('Ganti Sandi');
    }
}

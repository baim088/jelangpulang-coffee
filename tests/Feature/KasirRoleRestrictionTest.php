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
}

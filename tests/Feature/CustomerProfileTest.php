<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Database\Seeders\JelangPulangSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JelangPulangSeeder::class);
    }

    public function test_tamu_belum_login_diredirect_ke_login_saat_akses_profil(): void
    {
        $response = $this->get('/profil');
        $response->assertRedirect('/login');
    }

    public function test_pelanggan_dapat_melihat_dashboard_dan_statistik_profil(): void
    {
        $user = User::where('role', 'user')->first();

        // Buat pesanan dummy untuk user ini
        Order::create([
            'order_no' => 'JP-PROF-0001',
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'channel' => 'self_order',
            'status' => 'selesai',
            'payment_method' => 'qris',
            'subtotal' => 25000,
            'total' => 25000,
        ]);

        $response = $this->actingAs($user)->get('/profil');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Akun Saya');
        $response->assertSee($user->name);
        $response->assertSee('25.000');
        $response->assertSee('1 Kali');
    }

    public function test_pelanggan_dapat_memperbarui_nama_dan_kontak(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->put('/profil', [
            'name'  => 'Pelanggan Setia Baru',
            'email' => 'setia@jelangpulang.test',
            'phone' => '089988776655',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Pelanggan Setia Baru', $user->name);
        $this->assertEquals('setia@jelangpulang.test', $user->email);
        $this->assertEquals('089988776655', $user->phone);
    }

    public function test_pelanggan_gagal_ganti_password_jika_password_lama_salah(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->put('/profil/password', [
            'current_password'      => 'passwordsalah',
            'password'              => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_pelanggan_berhasil_ganti_password_baru(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->put('/profil/password', [
            'current_password'      => 'password',
            'password'              => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('passwordbaru123', $user->fresh()->password));
    }
}

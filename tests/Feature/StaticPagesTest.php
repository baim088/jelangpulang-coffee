<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaticPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\JelangPulangSeeder::class);
    }

    public function test_halaman_tentang_kami_dapat_diakses()
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('Kisah di Balik Secangkir Jeda');
        $response->assertSee('Perjalanan JelangPulang');
        $response->assertSee('Sangrai Bersahabat');
        $response->assertSee('Rendra &amp; Alya', false);
    }

    public function test_halaman_kontak_dapat_diakses()
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertSee('Hubungi Kami');
        $response->assertSee('Titik Temu Kedai');
        $response->assertSee('Kenyamanan yang Tersedia');
        $response->assertSee('Kirimkan Sapaan atau Pertanyaan');
        $response->assertDontSee('Kanal Percakapan Cepat');
    }

    public function test_navigasi_desktop_dan_mobile_memuat_tautan_tentang_dan_kontak()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(route('about'));
        $response->assertSee(route('contact'));
        $response->assertSee('Tentang Kami');
        $response->assertSee('Kontak');
        $response->assertSee('Kerap Ditanyakan Sebelum Singgah');
    }
}

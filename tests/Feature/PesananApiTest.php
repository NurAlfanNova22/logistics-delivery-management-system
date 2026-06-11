<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pesanan;

class PesananApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_pesanan_successfully()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/pesanan', [
                'nama_pabrik' => 'Pabrik Tester',
                'alamat_asal' => 'Alamat Asal @-8.09,112.16',
                'alamat_tujuan' => 'Alamat Tujuan @-7.98,112.62',
                'jenis_barang' => 'Pupuk',
                'berat' => 5000,
                'total_biaya' => 750000,
                'tanggal_pemesanan' => '2026-06-15',
                'estimasi_datang' => '1-2 Hari'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('pesanans', [
            'nama_pabrik' => 'Pabrik Tester',
            'berat' => 5000,
            'tanggal_pemesanan' => '2026-06-15 00:00:00',
            'estimasi_datang' => '1-2 Hari'
        ]);
    }
}

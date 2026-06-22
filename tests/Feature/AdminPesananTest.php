<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pesanan;

class AdminPesananTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_orders_by_customer_and_all_statuses()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customerA = User::factory()->create(['role' => 'customer', 'name' => 'Customer A']);
        $customerB = User::factory()->create(['role' => 'customer', 'name' => 'Customer B']);

        // Order 1: Customer A, status SELESAI
        Pesanan::create([
            'user_id' => $customerA->id,
            'resi' => 'LEX-A',
            'nama_pabrik' => 'Pabrik A',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 100000,
            'status' => 'SELESAI',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);

        // Order 2: Customer B, status DITOLAK
        Pesanan::create([
            'user_id' => $customerB->id,
            'resi' => 'LEX-B',
            'nama_pabrik' => 'Pabrik B',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'B', 'berat' => 200,
            'total_biaya' => 200000,
            'status' => 'DITOLAK',
            'status_pembayaran' => 'BELUM DIBAYAR'
        ]);

        // Order 3: Customer B, status DIBATALKAN
        Pesanan::create([
            'user_id' => $customerB->id,
            'resi' => 'LEX-C',
            'nama_pabrik' => 'Pabrik C',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'C', 'berat' => 300,
            'total_biaya' => 300000,
            'status' => 'DIBATALKAN',
            'status_pembayaran' => 'BELUM DIBAYAR'
        ]);

        // 1. Filter by Customer A
        $response = $this->actingAs($admin)
            ->get('/admin/pesanan?customer_id=' . $customerA->id);
        $response->assertStatus(200);
        $response->assertSee('LEX-A');
        $response->assertDontSee('LEX-B');
        $response->assertDontSee('LEX-C');

        // 2. Filter by Customer B
        $response = $this->actingAs($admin)
            ->get('/admin/pesanan?customer_id=' . $customerB->id);
        $response->assertStatus(200);
        $response->assertSee('LEX-B');
        $response->assertSee('LEX-C');
        $response->assertDontSee('LEX-A');

        // 3. Filter by Status DITOLAK
        $response = $this->actingAs($admin)
            ->get('/admin/pesanan?status=DITOLAK');
        $response->assertStatus(200);
        $response->assertSee('LEX-B');
        $response->assertDontSee('LEX-A');
        $response->assertDontSee('LEX-C');

        // 4. Filter by Status DIBATALKAN
        $response = $this->actingAs($admin)
            ->get('/admin/pesanan?status=DIBATALKAN');
        $response->assertStatus(200);
        $response->assertSee('LEX-C');
        $response->assertDontSee('LEX-A');
        $response->assertDontSee('LEX-B');
    }

    public function test_admin_can_filter_orders_by_date_range()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Order 1: Tanggal 2026-04-10
        $p1 = new Pesanan([
            'resi' => 'LEX-APR-10',
            'nama_pabrik' => 'Pabrik A',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 100000,
            'status' => 'SELESAI',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);
        $p1->created_at = '2026-04-10 10:00:00';
        $p1->save();

        // Order 2: Tanggal 2026-04-22
        $p2 = new Pesanan([
            'resi' => 'LEX-APR-22',
            'nama_pabrik' => 'Pabrik B',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'B', 'berat' => 200,
            'total_biaya' => 200000,
            'status' => 'SELESAI',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);
        $p2->created_at = '2026-04-22 10:00:00';
        $p2->save();

        // Order 3: Tanggal 2026-05-05
        $p3 = new Pesanan([
            'resi' => 'LEX-MAY-05',
            'nama_pabrik' => 'Pabrik C',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'C', 'berat' => 300,
            'total_biaya' => 300000,
            'status' => 'SELESAI',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);
        $p3->created_at = '2026-05-05 10:00:00';
        $p3->save();

        // Filter range: 2026-04-01 s/d 2026-04-25 (Hanya LEX-APR-10 dan LEX-APR-22 yang muncul)
        $response = $this->actingAs($admin)
            ->get('/admin/pesanan?start_date=2026-04-01&end_date=2026-04-25');
        $response->assertStatus(200);
        $response->assertSee('LEX-APR-10');
        $response->assertSee('LEX-APR-22');
        $response->assertDontSee('LEX-MAY-05');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pesanan;

class LaporanKeuanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_financial_reports_by_customer()
    {
        // 1. Create Admin
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Create Customers
        $customerA = User::factory()->create(['role' => 'customer', 'name' => 'Customer Alpha']);
        $customerB = User::factory()->create(['role' => 'customer', 'name' => 'Customer Beta']);

        // 3. Create Orders
        // Order for Customer A (Paid / Pemasukan Lunas)
        Pesanan::create([
            'user_id' => $customerA->id,
            'resi' => 'LEX111111111',
            'nama_pabrik' => 'Pabrik Alpha',
            'alamat_asal' => 'Alamat Asal Alpha @-8.09,112.16',
            'alamat_tujuan' => 'Alamat Tujuan Alpha @-7.98,112.62',
            'jenis_barang' => 'Barang A',
            'berat' => 1000,
            'total_biaya' => 100000,
            'status' => 'SELESAI',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);

        // Order for Customer B (Unpaid / Tagihan Pending)
        Pesanan::create([
            'user_id' => $customerB->id,
            'resi' => 'LEX222222222',
            'nama_pabrik' => 'Pabrik Beta',
            'alamat_asal' => 'Alamat Asal Beta @-8.09,112.16',
            'alamat_tujuan' => 'Alamat Tujuan Beta @-7.98,112.62',
            'jenis_barang' => 'Barang B',
            'berat' => 2000,
            'total_biaya' => 250000,
            'status' => 'AKTIF',
            'status_pembayaran' => 'BELUM DIBAYAR'
        ]);

        // 4. Access Financial Report as Admin with Customer A filter
        $response = $this->actingAs($admin)
            ->get('/admin/laporan-keuangan?customer_id=' . $customerA->id);

        $response->assertStatus(200);
        $response->assertViewHas('totalLunas', 100000);
        $response->assertViewHas('totalPending', 0);
        $response->assertSee('Pabrik Alpha');
        $response->assertDontSee('Pabrik Beta');

        // 5. Access Financial Report as Admin with Customer B filter
        $response = $this->actingAs($admin)
            ->get('/admin/laporan-keuangan?customer_id=' . $customerB->id);

        $response->assertStatus(200);
        $response->assertViewHas('totalLunas', 0);
        $response->assertViewHas('totalPending', 250000);
        $response->assertSee('Pabrik Beta');
        $response->assertDontSee('Pabrik Alpha');
    }

    public function test_admin_can_export_pdf_filtered_by_customer()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customerA = User::factory()->create(['role' => 'customer', 'name' => 'Customer Alpha']);

        Pesanan::create([
            'user_id' => $customerA->id,
            'resi' => 'LEX111111111',
            'nama_pabrik' => 'Pabrik Alpha',
            'alamat_asal' => 'Alamat Asal Alpha @-8.09,112.16',
            'alamat_tujuan' => 'Alamat Tujuan Alpha @-7.98,112.62',
            'jenis_barang' => 'Barang A',
            'berat' => 1000,
            'total_biaya' => 100000,
            'status' => 'SELESAI',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);

        $response = $this->actingAs($admin)
            ->get('/admin/laporan-keuangan/export-pdf?customer_id=' . $customerA->id);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}

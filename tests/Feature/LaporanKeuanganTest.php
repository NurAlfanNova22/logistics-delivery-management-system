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

    public function test_cancelled_and_rejected_orders_are_excluded_from_totals()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        // Order 1: Selesai - Lunas (should be included)
        Pesanan::create([
            'user_id' => $customer->id,
            'resi' => 'LEX111',
            'nama_pabrik' => 'Pabrik A',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 100000,
            'status' => 'SELESAI',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);

        // Order 2: Dibatalkan - Lunas (should be excluded)
        Pesanan::create([
            'user_id' => $customer->id,
            'resi' => 'LEX222',
            'nama_pabrik' => 'Pabrik A',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 200000,
            'status' => 'DIBATALKAN',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);

        // Order 3: Ditolak - Belum Bayar (should be excluded)
        Pesanan::create([
            'user_id' => $customer->id,
            'resi' => 'LEX333',
            'nama_pabrik' => 'Pabrik A',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 300000,
            'status' => 'DITOLAK',
            'status_pembayaran' => 'BELUM DIBAYAR'
        ]);

        // Order 4: Aktif - Belum Bayar (should be included)
        Pesanan::create([
            'user_id' => $customer->id,
            'resi' => 'LEX444',
            'nama_pabrik' => 'Pabrik A',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 400000,
            'status' => 'AKTIF',
            'status_pembayaran' => 'BELUM DIBAYAR'
        ]);

        $response = $this->actingAs($admin)->get('/admin/laporan-keuangan');

        $response->assertStatus(200);
        // totalLunas should be 100,000 (excluding 200,000)
        $response->assertViewHas('totalLunas', 100000);
        // totalPending should be 400,000 (excluding 300,000)
        $response->assertViewHas('totalPending', 400000);
    }

    public function test_admin_can_filter_financial_reports_by_payment_and_order_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        // Order 1: Selesai - Lunas
        Pesanan::create([
            'user_id' => $customer->id,
            'resi' => 'LEX111',
            'nama_pabrik' => 'Pabrik A',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 100000,
            'status' => 'SELESAI',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);

        // Order 2: Aktif - Belum Bayar
        Pesanan::create([
            'user_id' => $customer->id,
            'resi' => 'LEX222',
            'nama_pabrik' => 'Pabrik B',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 200000,
            'status' => 'AKTIF',
            'status_pembayaran' => 'BELUM DIBAYAR'
        ]);

        // Filter by Lunas
        $response = $this->actingAs($admin)->get('/admin/laporan-keuangan?status_pembayaran=SUDAH DIBAYAR');
        $response->assertStatus(200);
        $response->assertSee('Pabrik A');
        $response->assertDontSee('Pabrik B');

        // Filter by Status Aktif
        $response = $this->actingAs($admin)->get('/admin/laporan-keuangan?status=AKTIF');
        $response->assertStatus(200);
        $response->assertSee('Pabrik B');
        $response->assertDontSee('Pabrik A');
    }

    public function test_admin_can_view_rekapitulasi_per_customer_and_export_rekap_pdf()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customerA = User::factory()->create(['role' => 'customer', 'name' => 'Customer Alpha']);
        $customerB = User::factory()->create(['role' => 'customer', 'name' => 'Customer Beta']);

        // Order 1: Customer A - Lunas
        Pesanan::create([
            'user_id' => $customerA->id,
            'resi' => 'LEX-A1',
            'nama_pabrik' => 'Pabrik A',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 100000,
            'status' => 'SELESAI',
            'status_pembayaran' => 'SUDAH DIBAYAR'
        ]);

        // Order 2: Customer A - Belum Bayar
        Pesanan::create([
            'user_id' => $customerA->id,
            'resi' => 'LEX-A2',
            'nama_pabrik' => 'Pabrik A',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'A', 'berat' => 100,
            'total_biaya' => 50000,
            'status' => 'AKTIF',
            'status_pembayaran' => 'BELUM DIBAYAR'
        ]);

        // Order 3: Customer B - Belum Bayar
        Pesanan::create([
            'user_id' => $customerB->id,
            'resi' => 'LEX-B1',
            'nama_pabrik' => 'Pabrik B',
            'alamat_asal' => 'Asal', 'alamat_tujuan' => 'Tujuan', 'jenis_barang' => 'B', 'berat' => 200,
            'total_biaya' => 200000,
            'status' => 'AKTIF',
            'status_pembayaran' => 'BELUM DIBAYAR'
        ]);

        // Access the reports page
        $response = $this->actingAs($admin)->get('/admin/laporan-keuangan');
        $response->assertStatus(200);
        $response->assertViewHas('rekapCustomer');

        // Check view data content
        $rekapCustomer = $response->viewData('rekapCustomer');
        $this->assertCount(2, $rekapCustomer);

        $rekapA = $rekapCustomer->firstWhere('user_id', $customerA->id);
        $rekapB = $rekapCustomer->firstWhere('user_id', $customerB->id);

        $this->assertNotNull($rekapA);
        $this->assertNotNull($rekapB);

        $this->assertEquals(2, $rekapA->total_pesanan);
        $this->assertEquals(100000, $rekapA->total_lunas);
        $this->assertEquals(50000, $rekapA->total_pending);

        $this->assertEquals(1, $rekapB->total_pesanan);
        $this->assertEquals(0, $rekapB->total_lunas);
        $this->assertEquals(200000, $rekapB->total_pending);

        // Try to export rekap PDF
        $pdfResponse = $this->actingAs($admin)->get('/admin/laporan-keuangan/export-pdf?mode=rekap');
        $pdfResponse->assertStatus(200);
        $pdfResponse->assertHeader('content-type', 'application/pdf');
    }
}

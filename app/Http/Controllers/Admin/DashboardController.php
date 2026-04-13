<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Sopir;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPesanan = Pesanan::count();
        $aktif = Pesanan::where('status', 'LIKE', '%aktif%')->count();
        $selesai = Pesanan::where('status', 'LIKE', '%selesai%')->count();
        $menunggu = Pesanan::where('status', 'LIKE', '%menunggu%')->count();

        $totalSopir = Sopir::count();
        $totalKendaraan = Kendaraan::count();

        // Data Grafik: Pemasukan 7 Hari Terakhir
        $pemasukan7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $total = Pesanan::whereDate('created_at', $date)
                ->where('status_pembayaran', 'SUDAH DIBAYAR')
                ->sum('total_biaya');
            
            $pemasukan7Hari[] = [
                'label' => $date->format('d M'),
                'total' => $total
            ];
        }

        // Data Grafik: Komposisi Status
        $statusComposition = [
            'Menunggu' => Pesanan::where('status', 'LIKE', '%menunggu%')->count(),
            'Aktif' => Pesanan::where('status', 'AKTIF')->count(),
            'Selesai' => Pesanan::where('status', 'SELESAI')->count(),
            'Dibatalkan' => Pesanan::where('status', 'DIBATALKAN')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalPesanan',
            'aktif',
            'selesai',
            'menunggu',
            'totalSopir',
            'totalKendaraan',
            'pemasukan7Hari',
            'statusComposition'
        ));
    }
}
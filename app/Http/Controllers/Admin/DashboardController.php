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

        // Statistik Hari Ini & Bulan Ini
        $pesananHariIni = Pesanan::whereDate('created_at', Carbon::today())->count();
        $pesananBulanIni = Pesanan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        $pendapatanHariIni = Pesanan::whereDate('created_at', Carbon::today())
            ->where('status_pembayaran', 'SUDAH DIBAYAR')
            ->sum('total_biaya');
            
        $pendapatanBulanIni = Pesanan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status_pembayaran', 'SUDAH DIBAYAR')
            ->sum('total_biaya');

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

        // Data Grafik: Top 5 Sopir (Berdasarkan jumlah pesanan selesai)
        $topDrivers = Sopir::select('sopirs.nama', DB::raw('count(pesanans.id) as total'))
            ->join('pesanans', 'sopirs.id', '=', 'pesanans.sopir_id')
            ->where('pesanans.status', 'SELESAI')
            ->groupBy('sopirs.id', 'sopirs.nama')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Data Grafik: Top 5 Customer (Berdasarkan jumlah pesanan)
        $topCustomers = Pesanan::select('nama_pabrik', DB::raw('count(id) as total'))
            ->groupBy('nama_pabrik')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPesanan',
            'aktif',
            'selesai',
            'menunggu',
            'totalSopir',
            'totalKendaraan',
            'pemasukan7Hari',
            'statusComposition',
            'topDrivers',
            'topCustomers',
            'pesananHariIni',
            'pesananBulanIni',
            'pendapatanHariIni',
            'pendapatanBulanIni'
        ));
    }
}
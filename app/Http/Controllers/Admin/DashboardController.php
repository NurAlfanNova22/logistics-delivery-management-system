<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Sopir;
use App\Models\Kendaraan;

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

        return view('admin.dashboard', compact(
            'totalPesanan',
            'aktif',
            'selesai',
            'menunggu',
            'totalSopir',
            'totalKendaraan'
        ));
    }
}
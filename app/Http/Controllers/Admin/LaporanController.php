<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function keuangan(Request $request)
    {
        $query = Pesanan::query();

        // Filter Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Ambil data untuk tabel
        $transaksi = $query->orderBy('created_at', 'desc')->paginate(15);

        // Hitung Ringkasan
        $pemasukanLunas = Pesanan::where('status_pembayaran', 'SUDAH DIBAYAR');
        $tagihanPending = Pesanan::where('status_pembayaran', 'BELUM DIBAYAR');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $pemasukanLunas->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
            $tagihanPending->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $totalLunas = $pemasukanLunas->sum('total_biaya');
        $totalPending = $tagihanPending->sum('total_biaya');

        return view('admin.laporan.keuangan', compact('transaksi', 'totalLunas', 'totalPending'));
    }
}

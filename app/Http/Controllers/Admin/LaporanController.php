<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function exportPdf(Request $request)
    {
        $query = Pesanan::query();
        $pemasukanLunas = Pesanan::where('status_pembayaran', 'SUDAH DIBAYAR');
        $tagihanPending = Pesanan::where('status_pembayaran', 'BELUM DIBAYAR');

        $periode = 'Semua Waktu';

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
            $pemasukanLunas->whereBetween('created_at', [$startDate, $endDate]);
            $tagihanPending->whereBetween('created_at', [$startDate, $endDate]);

            $periode = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        }

        // Ambil semua data (tanpa pagination) untuk diekspor
        $transaksi = $query->orderBy('created_at', 'desc')->get();
        $totalLunas = $pemasukanLunas->sum('total_biaya');
        $totalPending = $tagihanPending->sum('total_biaya');

        $pdf = Pdf::loadView('admin.laporan.pdf_keuangan', compact('transaksi', 'totalLunas', 'totalPending', 'periode'));
        
        // Kustomisasi ukuran kertas atau orientasi jika diperlukan
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('laporan-keuangan-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function kinerjaSopir(Request $request)
    {
        $startDate = null;
        $endDate = null;
        $periode = 'Semua Waktu';

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $periode = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        }

        $sopirs = \App\Models\Sopir::with(['kendaraan'])
            ->withCount(['pesanans as total_selesai' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'SELESAI');
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_selesai', [$startDate, $endDate]);
                }
            }])
            ->withSum(['pesanans as total_pendapatan' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'SELESAI');
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_selesai', [$startDate, $endDate]);
                }
            }], 'total_biaya')
            ->orderBy('total_selesai', 'desc')
            ->get();

        return view('admin.laporan.kinerja_sopir', compact('sopirs', 'periode'));
    }

    public function exportKinerjaSopir(Request $request)
    {
        $startDate = null;
        $endDate = null;
        $periode = 'Semua Waktu';

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $periode = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        }

        $sopirs = \App\Models\Sopir::with(['kendaraan'])
            ->withCount(['pesanans as total_selesai' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'SELESAI');
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_selesai', [$startDate, $endDate]);
                }
            }])
            ->withSum(['pesanans as total_pendapatan' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'SELESAI');
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_selesai', [$startDate, $endDate]);
                }
            }], 'total_biaya')
            ->orderBy('total_selesai', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf_kinerja_sopir', compact('sopirs', 'periode'));
        return $pdf->download('laporan-kinerja-sopir-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}

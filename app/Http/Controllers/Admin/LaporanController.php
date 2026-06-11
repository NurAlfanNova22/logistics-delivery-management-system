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
        $pemasukanLunas = Pesanan::where('status_pembayaran', 'SUDAH DIBAYAR');
        $tagihanPending = Pesanan::where('status_pembayaran', 'BELUM DIBAYAR');

        // Filter Customer
        if ($request->filled('customer_id')) {
            $query->where('user_id', $request->customer_id);
            $pemasukanLunas->where('user_id', $request->customer_id);
            $tagihanPending->where('user_id', $request->customer_id);
        }

        // Filter Bulan & Tahun
        if ($request->filled('month') && $request->filled('year')) {
            $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($request->year, $request->month, 1)->endOfMonth();

            $query->whereBetween('created_at', [$startDate, $endDate]);
            $pemasukanLunas->whereBetween('created_at', [$startDate, $endDate]);
            $tagihanPending->whereBetween('created_at', [$startDate, $endDate]);
        } 
        // Filter Tanggal Custom
        elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
            $pemasukanLunas->whereBetween('created_at', [$startDate, $endDate]);
            $tagihanPending->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Ambil data untuk tabel
        $transaksi = $query->orderBy('created_at', 'desc')->paginate(15);

        $totalLunas = $pemasukanLunas->sum('total_biaya');
        $totalPending = $tagihanPending->sum('total_biaya');

        $customers = \App\Models\User::where('role', 'customer')->orderBy('name')->get();

        return view('admin.laporan.keuangan', compact('transaksi', 'totalLunas', 'totalPending', 'customers'));
    }

    public function exportPdf(Request $request)
    {
        $query = Pesanan::query();
        $pemasukanLunas = Pesanan::where('status_pembayaran', 'SUDAH DIBAYAR');
        $tagihanPending = Pesanan::where('status_pembayaran', 'BELUM DIBAYAR');

        $periode = 'Semua Waktu';
        $selectedCustomerName = 'Semua Customer';

        // Filter Customer
        if ($request->filled('customer_id')) {
            $query->where('user_id', $request->customer_id);
            $pemasukanLunas->where('user_id', $request->customer_id);
            $tagihanPending->where('user_id', $request->customer_id);

            $cust = \App\Models\User::find($request->customer_id);
            if ($cust) {
                $selectedCustomerName = $cust->name;
            }
        }

        // Filter Bulan & Tahun
        if ($request->filled('month') && $request->filled('year')) {
            $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($request->year, $request->month, 1)->endOfMonth();

            $query->whereBetween('created_at', [$startDate, $endDate]);
            $pemasukanLunas->whereBetween('created_at', [$startDate, $endDate]);
            $tagihanPending->whereBetween('created_at', [$startDate, $endDate]);

            $periode = $startDate->translatedFormat('F Y');
        } 
        // Filter Tanggal Custom
        elseif ($request->filled('start_date') && $request->filled('end_date')) {
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

        $pdf = Pdf::loadView('admin.laporan.pdf_keuangan', compact('transaksi', 'totalLunas', 'totalPending', 'periode', 'selectedCustomerName'));
        
        // Kustomisasi ukuran kertas atau orientasi jika diperlukan
        $pdf->setPaper('A4', 'landscape');

        $filename = 'laporan-keuangan';
        if ($request->filled('customer_id')) {
            $filename .= '-' . \Str::slug($selectedCustomerName);
        }
        if ($request->filled('month') && $request->filled('year')) {
            $monthName = strtolower(Carbon::createFromDate($request->year, $request->month, 1)->translatedFormat('F'));
            $filename .= '-' . $monthName . '-' . $request->year;
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $filename .= '-' . $request->start_date . '-sd-' . $request->end_date;
        } else {
            $filename .= '-semua-waktu';
        }
        $filename .= '_' . Carbon::now()->format('His') . '.pdf';

        return $pdf->download($filename);
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

        $sopirs = \App\Models\Sopir::with(['kendaraan', 'pesanans' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'SELESAI');
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_selesai', [$startDate, $endDate]);
                }
                $query->orderBy('tanggal_selesai', 'desc');
            }])
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
            ->withSum(['pesanans as total_berat' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'SELESAI');
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_selesai', [$startDate, $endDate]);
                }
            }], 'berat')
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

        $sopirs = \App\Models\Sopir::with(['kendaraan', 'pesanans' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'SELESAI');
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_selesai', [$startDate, $endDate]);
                }
                $query->orderBy('tanggal_selesai', 'desc');
            }])
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
            ->withSum(['pesanans as total_berat' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'SELESAI');
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_selesai', [$startDate, $endDate]);
                }
            }], 'berat')
            ->orderBy('total_selesai', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf_kinerja_sopir', compact('sopirs', 'periode'));
        
        $filename = 'laporan-kinerja-sopir';
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $filename .= '-' . $request->start_date . '-sd-' . $request->end_date;
        } else {
            $filename .= '-semua-waktu';
        }
        $filename .= '_' . Carbon::now()->format('His') . '.pdf';

        return $pdf->download($filename);
    }
}

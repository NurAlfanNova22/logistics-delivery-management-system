<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Checkpoint;

class PesananApiController extends Controller
{
    public function index()
    {
        return response()->json(Pesanan::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pabrik' => 'required',
            'alamat_asal' => 'required',
            'alamat_tujuan' => 'required',
            'jenis_barang' => 'required',
            'berat' => 'required|integer'
        ]);

        $tanggal = date('ymd');

        $lastOrder = Pesanan::whereDate('created_at', today())
            ->orderBy('id','desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->resi, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        $resi = 'LEX' . $tanggal . $newNumber;

        $pesanan = Pesanan::create([
            'resi' => $resi,
            'nama_pabrik' => $request->nama_pabrik,
            'alamat_asal' => $request->alamat_asal,
            'alamat_tujuan' => $request->alamat_tujuan,
            'jenis_barang' => $request->jenis_barang,
            'berat' => $request->berat,
            'status' => 'MENUNGGU KONFIRMASI'
        ]);

        return response()->json($pesanan);
    }

    public function driverOrders($sopir_id)
    {
        // KUNCI PENGAMANAN: Pastikan API menolak request jika ID supir tidak valid (0 atau null)
        if (!$sopir_id || (int) $sopir_id <= 0) {
            return response()->json([]);
        }

        $orders = Pesanan::where('sopir_id', $sopir_id)
            ->whereNotNull('sopir_id') // Wajib sudah ter-assign
            ->orderBy('created_at','desc')
            ->get();

        return response()->json($orders);
    }

    public function updateStatusPengiriman($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status_pengiriman == 'MENUNGGU PICKUP') {
            $pesanan->status_pengiriman = 'DALAM PERJALANAN';
        } elseif ($pesanan->status_pengiriman == 'DALAM PERJALANAN') {
            $pesanan->status_pengiriman = 'PESANAN TELAH DIKIRIM';
        }

        $pesanan->save();

        return response()->json([
            'message' => 'Status pengiriman berhasil diupdate',
            'data' => $pesanan
        ]);
    }

    public function selesaikanPesanan($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = 'SELESAI';
        $pesanan->save();

        return response()->json([
            'message' => 'Pesanan berhasil diselesaikan oleh pelanggan',
            'data' => $pesanan
        ]);
    }

    public function batalkanPesanan($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Cek jika pesanan sudah diambil sopir
        $statusP = strtoupper($pesanan->status_pengiriman ?? '');
        if ($statusP == 'DALAM PERJALANAN' || $statusP == 'PESANAN TELAH DIKIRIM' || $pesanan->status == 'SELESAI') {
            return response()->json([
                'status' => false,
                'message' => 'Gagal dibatalkan: Pesanan sudah dikirim atau selesai.'
            ], 400);
        }

        $pesanan->status = 'DIBATALKAN';
        $pesanan->save();

        return response()->json([
            'status' => true,
            'message' => 'Pesanan berhasil dibatalkan.'
        ]);
    }

    public function trackingResi($resi)
    {
        $pesanan = Pesanan::with('checkpoints')->where('resi', $resi)->first();

        if (!$pesanan) {
            return response()->json([
                'status' => false,
                'message' => 'Resi tidak ditemukan'
            ], 404);
        }

        // Base progress steps
        $progress = [
            [
                'step' => 'Pesanan Dibuat',
                'lokasi' => $pesanan->alamat_asal,
                'status' => 'SELESAI',
                'waktu' => $pesanan->created_at->format('H:i')
            ]
        ];

        // Add dynamically calculated checkpoints from database
        foreach ($pesanan->checkpoints as $cp) {
            $progress[] = [
                'step' => 'Truk melintasi wilayah',
                'lokasi' => $cp->lokasi,
                'status' => 'SELESAI',
                'waktu' => $cp->created_at->format('H:i')
            ];
        }

        // Append current status progress
        if ($pesanan->status_pengiriman == 'MENUNGGU PICKUP') {
            $progress[] = ['step' => 'Sopir mengambil barang', 'lokasi' => $pesanan->alamat_asal, 'status' => 'PROSES', 'waktu' => '-'];
        } elseif ($pesanan->status_pengiriman == 'DALAM PERJALANAN') {
            $progress[] = ['step' => 'Dalam Perjalanan', 'lokasi' => 'Menuju ' . $pesanan->alamat_tujuan, 'status' => 'PROSES', 'waktu' => '-'];
        } elseif ($pesanan->status_pengiriman == 'PESANAN TELAH DIKIRIM') {
            $progress[] = ['step' => 'Barang Sampai', 'lokasi' => $pesanan->alamat_tujuan, 'status' => 'SELESAI', 'waktu' => $pesanan->updated_at->format('H:i')];
        }

        return response()->json([
            'status' => true,
            'resi' => $pesanan->resi,
            'nama_pabrik' => $pesanan->nama_pabrik,
            'asal' => $pesanan->alamat_asal,
            'tujuan' => $pesanan->alamat_tujuan,
            'status_pengiriman' => $pesanan->status_pengiriman,
            'progress' => array_reverse($progress) // Newest first like Shopee
        ]);
    }

    public function addCheckpoint(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'lokasi' => 'required',
            'lat' => 'nullable',
            'lng' => 'nullable'
        ]);

        $checkpoint = Checkpoint::create([
            'pesanan_id' => $request->pesanan_id,
            'lokasi' => $request->lokasi,
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Checkpoint berhasil ditambahkan',
            'data' => $checkpoint
        ]);
    }

    public function toggleOnline(Request $request)
    {
        $request->validate([
            'sopir_id' => 'required|exists:sopirs,id',
            'is_online' => 'required|boolean'
        ]);

        $sopir = \App\Models\Sopir::find($request->sopir_id);
        $sopir->is_online = $request->is_online;
        $sopir->save();

        return response()->json([
            'status' => true,
            'message' => 'Status online berhasil diubah',
            'is_online' => $sopir->is_online
        ]);
    }

    public function driverStats($sopir_id)
    {
        $today = now()->toDateString();
        $bulan = now()->month;
        $tahun = now()->year;

        $hariIni = Pesanan::where('sopir_id', $sopir_id)
            ->where('status', 'SELESAI')
            ->whereDate('updated_at', $today)
            ->count();

        $bulanIni = Pesanan::where('sopir_id', $sopir_id)
            ->where('status', 'SELESAI')
            ->whereMonth('updated_at', $bulan)
            ->whereYear('updated_at', $tahun)
            ->count();

        return response()->json([
            'hari_ini' => $hariIni,
            'bulan_ini' => $bulanIni,
            'is_online' => \App\Models\Sopir::find($sopir_id)?->is_online ? true : false
        ]);
    }
}

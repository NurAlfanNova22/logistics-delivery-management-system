<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;

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

        // ambil resi terakhir hari ini
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
            'status' => 'MENUNGGU'
        ]);

        return response()->json($pesanan);
    }

    public function driverOrders($sopir_id)
    {
        $orders = Pesanan::where('sopir_id', $sopir_id)
            ->where('status', 'AKTIF')
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

            $pesanan->status_pengiriman = 'SELESAI';
            $pesanan->status = 'SELESAI';

        }

        $pesanan->save();

        return response()->json([
            'message' => 'Status pengiriman berhasil diupdate',
            'data' => $pesanan
        ]);
    }

    public function trackingResi($resi)
    {
        $pesanan = Pesanan::where('resi', $resi)->first();

        if (!$pesanan) {
            return response()->json([
                'status' => false,
                'message' => 'Resi tidak ditemukan'
            ], 404);
        }

        // progress pengiriman
        $progress = [
            [
                'step' => 'Order Dibuat',
                'status' => 'SELESAI'
            ],
            [
                'step' => 'Driver Mengambil Barang',
                'status' => $pesanan->status_pengiriman == 'MENUNGGU PICKUP' ? 'PROSES' : 'SELESAI'
            ],
            [
                'step' => 'Dalam Perjalanan',
                'status' => $pesanan->status_pengiriman == 'DALAM PERJALANAN' ? 'PROSES' : 
                        ($pesanan->status_pengiriman == 'SELESAI' ? 'SELESAI' : 'MENUNGGU')
            ],
            [
                'step' => 'Barang Sampai',
                'status' => $pesanan->status_pengiriman == 'SELESAI' ? 'SELESAI' : 'MENUNGGU'
            ]
        ];

        return response()->json([
            'status' => true,
            'resi' => $pesanan->resi,
            'nama_pabrik' => $pesanan->nama_pabrik,
            'asal' => $pesanan->alamat_asal,
            'tujuan' => $pesanan->alamat_tujuan,
            'status_pengiriman' => $pesanan->status_pengiriman,
            'progress' => $progress
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
            'bulan_ini' => $bulanIni
        ]);
    }
}

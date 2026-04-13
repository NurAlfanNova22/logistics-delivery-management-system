<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Sopir;
use App\Models\Kendaraan;

class PesananController extends Controller
{
    public function assignForm($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $sopir = Sopir::all();
        $kendaraan = Kendaraan::all();

        return view('admin.pesanan.assign', compact('pesanan','sopir','kendaraan'));
    }

    public function assignStore(Request $request, $id)
    {
        $request->validate([
            'sopir_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $sopir = \App\Models\Sopir::find($value);
                    if ($sopir && !$sopir->is_online) {
                        $fail('Sopir yang dipilih sedang Offline. Menunggu sopir aktif.');
                    }
                },
            ],
            'kendaraan_id' => 'required'
        ]);

        $pesanan = Pesanan::findOrFail($id);

        $pesanan->sopir_id = $request->sopir_id;
        $pesanan->kendaraan_id = $request->kendaraan_id;

        // status order
        $pesanan->status = 'AKTIF';

        // status perjalanan driver
        $pesanan->status_pengiriman = 'MENUNGGU PICKUP';

        $pesanan->save();

        return redirect()->route('pesanan.index')
            ->with('success','Pesanan berhasil di-assign');
    }

    public function index(Request $request)
    {
        $query = Pesanan::with('sopir.kendaraan');

        if ($request->status) {
            $query->where('status', 'LIKE', '%' . $request->status . '%');
        }

        if ($request->resi) {
            $query->where('resi', 'LIKE', '%' . $request->resi . '%');
        }

        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->tanggal_sampai) {
            $query->whereDate('updated_at', $request->tanggal_sampai)
                  ->where('status', 'SELESAI');
        }

        $pesanan = $query->latest()->paginate(10);

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['sopir.kendaraan', 'checkpoints' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatusForm($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        return view('admin.pesanan.update-status', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = $request->status;
        $pesanan->save();

        return redirect()->route('pesanan.index')
            ->with('success', 'Status berhasil diperbarui');
    }
}

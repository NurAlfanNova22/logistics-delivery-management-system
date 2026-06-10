<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Sopir;
use App\Models\Kendaraan;
use App\Services\FirebaseService;

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

        try {
            $db = app(\App\Services\FirebaseService::class)->database();
            // Notifikasi ke Driver
            $db->getReference('notifications_driver/' . $pesanan->sopir_id)->push([
                'title' => 'Pesanan Baru! 🚛',
                'body' => 'Pengiriman baru (Resi: '.$pesanan->resi.') tujuan ke ' . $pesanan->alamat_tujuan . ' sudah ditugaskan kepada Anda.',
                'resi' => $pesanan->resi,
                'timestamp' => now()->timestamp * 1000,
            ]);

            // Notifikasi ke Customer
            $db->getReference('notifications_customer/' . $pesanan->user_id)->push([
                'title' => 'Supir Telah Ditugaskan! 🚛',
                'body' => 'Pesanan Anda (Resi: '.$pesanan->resi.') sedang disiapkan. Supir: ' . $pesanan->sopir->nama,
                'type' => 'driver_assigned',
                'data' => ['order_id' => $pesanan->id, 'resi' => $pesanan->resi],
                'timestamp' => now()->timestamp * 1000,
            ]);
        } catch (\Exception $e) {
            \Log::error('Firebase Notification Error: ' . $e->getMessage());
        }

        return redirect()->route('pesanan.index')
            ->with('success','Pesanan berhasil di-assign');
    }

    public function reassignForm($id)
    {
        $pesanan = Pesanan::with('sopir')->findOrFail($id);
        $sopir = Sopir::all();

        return view('admin.pesanan.reassign', compact('pesanan','sopir'));
    }

    public function reassignStore(Request $request, $id)
    {
        $request->validate([
            'sopir_id' => [
                'required',
                function ($attribute, $value, $fail) use ($id) {
                    $pesanan = Pesanan::find($id);
                    if ($pesanan && $pesanan->sopir_id == $value) {
                        $fail('Sopir baru tidak boleh sama dengan sopir saat ini.');
                    }
                    $sopir = \App\Models\Sopir::find($value);
                    if ($sopir && !$sopir->is_online) {
                        $fail('Sopir yang dipilih sedang Offline. Menunggu sopir aktif.');
                    }
                },
            ]
        ]);

        $pesanan = Pesanan::findOrFail($id);
        
        $pesanan->sopir_id = $request->sopir_id;
        $pesanan->save();

        try {
            $db = app(FirebaseService::class)->database();
            // Notifikasi ke Driver Baru
            $db->getReference('notifications_driver/' . $pesanan->sopir_id)->push([
                'title' => 'Pesanan Dialihkan ke Anda 🚛',
                'body' => 'Pengiriman (Resi: '.$pesanan->resi.') tujuan ke ' . $pesanan->alamat_tujuan . ' telah ditugaskan kepada Anda.',
                'resi' => $pesanan->resi,
                'timestamp' => now()->timestamp * 1000,
            ]);

            // Notifikasi ke Customer
            $db->getReference('notifications_customer/' . $pesanan->user_id)->push([
                'title' => 'Perubahan Supir 🚛',
                'body' => 'Supir untuk pesanan Anda (Resi: '.$pesanan->resi.') telah diperbarui menjadi: ' . $pesanan->sopir->nama,
                'type' => 'driver_changed',
                'data' => ['order_id' => $pesanan->id, 'resi' => $pesanan->resi],
                'timestamp' => now()->timestamp * 1000,
            ]);
        } catch (\Exception $e) {
            \Log::error('Firebase Notification Error: ' . $e->getMessage());
        }

        return redirect()->route('pesanan.index')
            ->with('success', 'Sopir berhasil diubah');
    }

    public function index(Request $request)
    {
        $query = Pesanan::with(['sopir.kendaraan', 'user']);

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
            $query->whereDate('tanggal_selesai', $request->tanggal_sampai)
                  ->where('status', 'SELESAI');
        }

        if ($request->status_pembayaran) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        $pesanan = $query->latest()->paginate(10);

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['sopir.kendaraan', 'checkpoints' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 'user'])->findOrFail($id);

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

        if ($request->status == 'SELESAI' && !$pesanan->tanggal_selesai) {
            $pesanan->tanggal_selesai = now();
        }

        $pesanan->save();

        // Kirim Notifikasi ke Customer via Firebase Realtime
        try {
            $db = app(\App\Services\FirebaseService::class)->database();
            $db->getReference('notifications_customer/' . $pesanan->user_id)->push([
                'title' => 'Update Status Pesanan 📦',
                'body' => 'Pesanan Anda (Resi: '.$pesanan->resi.') kini berstatus: ' . $pesanan->status,
                'type' => 'status_update',
                'data' => ['order_id' => $pesanan->id, 'resi' => $pesanan->resi],
                'timestamp' => now()->timestamp * 1000,
            ]);
        } catch (\Exception $e) {
            \Log::error('Firebase Notification Error: ' . $e->getMessage());
        }

        return redirect()->route('pesanan.index')
            ->with('success', 'Status berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Optional: you can also delete related notifications in Firebase here if needed,
        // but for now we just delete the order record from the database.
        
        $pesanan->delete();

        return redirect()->route('pesanan.index')
            ->with('success', 'Pesanan berhasil dihapus');
    }

    public function rejectStore(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = 'DITOLAK';
        $pesanan->alasan_penolakan = $request->alasan_penolakan;
        $pesanan->save();

        try {
            $db = app(\App\Services\FirebaseService::class)->database();
            // Notifikasi ke Customer
            $db->getReference('notifications_customer/' . $pesanan->user_id)->push([
                'title' => 'Pesanan Anda Ditolak ❌',
                'body' => 'Pesanan dengan resi ' . $pesanan->resi . ' ditolak oleh admin dengan alasan: ' . $request->alasan_penolakan,
                'type' => 'status_update',
                'data' => ['order_id' => $pesanan->id, 'resi' => $pesanan->resi],
                'timestamp' => now()->timestamp * 1000,
            ]);
        } catch (\Exception $e) {
            \Log::error('Firebase Notification Error: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Pesanan berhasil ditolak');
    }
}

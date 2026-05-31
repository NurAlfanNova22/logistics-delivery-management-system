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
        return response()->json(Pesanan::where('user_id', auth()->id())->latest()->get());
    }

    public function store(Request $request)
    {
        \Log::info("🚀 [STORE DEBUG] Menerima request buat pesanan dari User: " . auth()->id());
        $request->validate([
            'nama_pabrik' => 'required',
            'alamat_asal' => 'required',
            'alamat_tujuan' => 'required',
            'jenis_barang' => 'required',
            'berat' => 'required|integer',
            'total_biaya' => 'nullable|numeric'
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
            'user_id' => auth()->id(),
            'resi' => $resi,
            'nama_pabrik' => $request->nama_pabrik,
            'alamat_asal' => $request->alamat_asal,
            'alamat_tujuan' => $request->alamat_tujuan,
            'jenis_barang' => $request->jenis_barang,
            'berat' => $request->berat,
            'total_biaya' => $request->total_biaya ?? 0,
            'status' => 'MENUNGGU KONFIRMASI',
            'status_pembayaran' => 'BELUM DIBAYAR'
        ]);

        $this->createNotification(
            $pesanan->user_id,
            'Pesanan Berhasil Dibuat',
            "Pesanan Anda dengan resi {$pesanan->resi} telah berhasil dibuat dan menunggu konfirmasi admin.",
            'order_created',
            ['order_id' => $pesanan->id, 'resi' => $pesanan->resi]
        );

        // Push notifikasi pesanan baru untuk admin ke Firebase Realtime Database
        try {
            $db = app(\App\Services\FirebaseService::class)->database();
            $db->getReference('notifications_admin')->push([
                'title' => 'Pesanan Baru Masuk! 📦',
                'body' => "Pesanan dengan resi {$pesanan->resi} telah dibuat oleh " . (auth()->user()->name ?? 'Customer') . ".",
                'resi' => $pesanan->resi,
                'nama_pabrik' => $pesanan->nama_pabrik,
                'timestamp' => (int) (now()->timestamp * 1000)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error pushing notification to Admin Firebase: ' . $e->getMessage());
        }

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
            ->where('status', '!=', 'DIBATALKAN')
            ->orderBy('created_at','desc')
            ->get();

        return response()->json($orders);
    }

    public function updateStatusPengiriman($id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);
            
            if ($pesanan->status == 'DIBATALKAN') {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal update status: Pesanan telah dibatalkan oleh customer.'
                ], 400);
            }

            $statusSekarang = strtoupper($pesanan->status_pengiriman ?? '');

            if ($statusSekarang == 'MENUNGGU PICKUP' || $statusSekarang == '') {
                $pesanan->status_pengiriman = 'DALAM PERJALANAN';
                $pesanan->tanggal_dikirim = \Illuminate\Support\Carbon::now();
                
                $this->createNotification(
                    $pesanan->user_id,
                    'Pesanan Dalam Perjalanan',
                    "Sopir telah memulai pengiriman untuk pesanan {$pesanan->resi}. Silakan pantau di menu tracking.",
                    'order_shipped',
                    ['order_id' => $pesanan->id, 'resi' => $pesanan->resi]
                );
            } elseif ($statusSekarang == 'DALAM PERJALANAN') {
                $pesanan->status_pengiriman = 'PESANAN TELAH DIKIRIM';
                
                $this->createNotification(
                    $pesanan->user_id,
                    'Pesanan Telah Sampai',
                    "Pesanan {$pesanan->resi} telah sampai di lokasi tujuan. Silakan lakukan pembayaran jika belum lunas.",
                    'order_arrived',
                    ['order_id' => $pesanan->id, 'resi' => $pesanan->resi]
                );
                
                // Generate Midtrans Token only if not already generated & biaya > 0
                if ($pesanan->total_biaya > 0 && !$pesanan->snap_token) {
                    $this->ensureMidtransToken($pesanan);
                }
            }

            $pesanan->save();

            return response()->json([
                'success' => true,
                'message' => 'Status pengiriman berhasil diupdate',
                'data' => $pesanan
            ]);
        } catch (\Throwable $e) {
            \Log::error('Update Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage(),
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    private function ensureMidtransToken($pesanan)
    {
        try {
            if (class_exists('\Midtrans\Config')) {
                \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-x...');
                \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => $pesanan->resi . '-' . time(),
                        'gross_amount' => $pesanan->total_biaya,
                    ],
                    'customer_details' => [
                        'first_name' => $pesanan->nama_pabrik,
                    ],
                    // Tambahkan Callback URL secara eksplisit
                    'callbacks' => [
                        'finish' => 'lancarekspedisi://payment_finish'
                    ]
                ];

                $transaction = \Midtrans\Snap::createTransaction($params);
                $pesanan->snap_token = $transaction->token;
                $pesanan->payment_url = $transaction->redirect_url;
                $pesanan->save();
                return true;
            } else {
                \Log::warning('Midtrans Library not found on this server.');
            }
        } catch (\Throwable $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
        }
        return false;
    }

    public function selesaikanPesanan($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->total_biaya > 0 && strtoupper($pesanan->status_pembayaran) != 'SUDAH DIBAYAR') {
            return response()->json([
                'message' => 'Pesanan belum dibayar lunas. Harap lakukan pembayaran terlebih dahulu.',
            ], 400);
        }

        $pesanan->status = 'SELESAI';
        $pesanan->tanggal_selesai = \Illuminate\Support\Carbon::now();
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

        // 1. Kirim notifikasi ke Customer
        $this->createNotification(
            $pesanan->user_id,
            'Pesanan Dibatalkan ❌',
            "Pesanan Anda dengan resi {$pesanan->resi} telah berhasil dibatalkan.",
            'order_cancelled',
            ['order_id' => $pesanan->id, 'resi' => $pesanan->resi]
        );

        // 2. Kirim notifikasi ke Driver (jika sudah ter-assign)
        if ($pesanan->sopir_id) {
            try {
                $db = app(\App\Services\FirebaseService::class)->database();
                $db->getReference('notifications_driver/' . $pesanan->sopir_id)->push([
                    'title' => 'Pesanan Dibatalkan ❌',
                    'body' => "Pesanan dengan Resi: {$pesanan->resi} telah dibatalkan oleh customer.",
                    'resi' => $pesanan->resi,
                    'timestamp' => now()->timestamp * 1000,
                ]);
            } catch (\Exception $e) {
                \Log::error('Firebase Notification Error for Driver on Cancel: ' . $e->getMessage());
            }
        }

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

        // Auto-generate payment token if shipped but token is missing
        if ($pesanan->status_pengiriman == 'PESANAN TELAH DIKIRIM' && $pesanan->total_biaya > 0 && !$pesanan->snap_token) {
            $this->ensureMidtransToken($pesanan);
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
            'status_pengiriman' => $pesanan->status_pengiriman,
            'progress' => array_reverse($progress),
            'pesanan' => $pesanan
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
            ->where(function($q) {
                $q->where('status', 'SELESAI')
                  ->orWhere('status_pengiriman', 'PESANAN TELAH DIKIRIM');
            })
            ->whereDate('updated_at', $today)
            ->count();

        $bulanIni = Pesanan::where('sopir_id', $sopir_id)
            ->where(function($q) {
                $q->where('status', 'SELESAI')
                  ->orWhere('status_pengiriman', 'PESANAN TELAH DIKIRIM');
            })
            ->whereMonth('updated_at', $bulan)
            ->whereYear('updated_at', $tahun)
            ->count();

        return response()->json([
            'hari_ini' => $hariIni,
            'bulan_ini' => $bulanIni,
            'is_online' => \App\Models\Sopir::find($sopir_id)?->is_online ? true : false
        ]);
    }

    public function paymentCallback(Request $request)
    {
        try {
            $orderId = $request->order_id;

            // 1. Handle Test Notification from Midtrans Sandbox Dashboard
            if (isset($orderId) && (str_contains($orderId, 'test') || str_contains($orderId, 'M882436788'))) {
                return response()->json(['message' => 'Test/Simulation Notification Handled']);
            }

            if (!class_exists('\Midtrans\Config')) {
                return response()->json(['message' => 'Library Midtrans missing'], 500);
            }

            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-x...');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            
            $notif = new \Midtrans\Notification();
            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;
            
            // Extract original Resi from order_id (Format: RESI-TIME)
            $pesanan_resi = explode('-', $orderId)[0];
            $pesanan = Pesanan::where('resi', $pesanan_resi)->first();
            
            if (!$pesanan) return response()->json(['message' => 'Pesanan not found'], 404);

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $pesanan->status_pembayaran = 'SUDAH DIBAYAR';
                $pesanan->status = 'SELESAI';
                $pesanan->tanggal_selesai = \Illuminate\Support\Carbon::now();
                $pesanan->save();

                $this->createNotification(
                    $pesanan->user_id,
                    'Pembayaran Berhasil',
                    "Pembayaran untuk pesanan {$pesanan->resi} telah kami terima. Terima kasih!",
                    'payment_success',
                    ['order_id' => $pesanan->id, 'resi' => $pesanan->resi]
                );
                \Log::info("Payment SUCCESS for order: $orderId");
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $pesanan->status_pembayaran = 'GAGAL / EXPIRED';
                $pesanan->save();
                \Log::info("Payment FAILED for order: $orderId");
            }

            return response()->json(['message' => 'Callback Handled']);
        } catch (\Throwable $e) {
            \Log::error('Callback Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    private function createNotification($userId, $title, $body, $type = null, $data = null)
    {
        try {
            $user = \App\Models\User::find($userId);
            
            // 1. Simpan ke database MySQL (Riwayat)
            \App\Models\Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'type' => $type,
                'data' => $data
            ]);

            // 2. Kirim ke Firebase Realtime Database (Untuk Notifikasi Instan)
            try {
                $targetId = (int) $userId;
                \Log::info("🔔 [FIREBASE DEBUG] Mencoba kirim ke: notifications_customer/" . $targetId);
                
                $db = app(\App\Services\FirebaseService::class)->database();
                
                $pushData = [
                    'title' => (string) $title,
                    'body' => (string) $body,
                    'type' => (string) $type,
                    'data' => $data,
                    'timestamp' => (int) (now()->timestamp * 1000),
                ];
                
                $db->getReference('notifications_customer/' . $targetId)->push($pushData);
                \Log::info("✅ [FIREBASE DEBUG] Berhasil push data!");
            } catch (\Exception $fe) {
                \Log::error('❌ [FIREBASE DEBUG] Error: ' . $fe->getMessage());
                \Log::error($fe->getTraceAsString());
            }

            // 3. Kirim Push Notification via FCM (Backup jika aplikasi ditutup)
            if ($user && $user->fcm_token) {
                $this->sendFcmPush($user->fcm_token, $title, $body, $data);
            }
        } catch (\Throwable $e) {
            \Log::error('Create Notification Error: ' . $e->getMessage());
        }
    }

    private function sendFcmPush($token, $title, $body, $data = null)
    {
        $serverKey = env('FCM_SERVER_KEY');
        if (!$serverKey) return;

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default'
            ],
            'data' => $data ?? []
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sopir;
use App\Models\Kendaraan;
use App\Models\Checkpoint;

class Pesanan extends Model
{

    protected $fillable = [
        'resi',
        'nama_pabrik',
        'alamat_asal',
        'alamat_tujuan',
        'jenis_barang',
        'berat',
        'status',
        'status_pengiriman',
        'sopir_id',
        'kendaraan_id',
        'total_biaya',
        'status_pembayaran',
        'snap_token',
        'payment_url',
        'tanggal_selesai',
        'user_id',
        'tanggal_dikirim'
    ];

    protected $casts = [
        'tanggal_selesai' => 'datetime',
        'tanggal_dikirim' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sopir()
    {
        return $this->belongsTo(Sopir::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class);
    }

}

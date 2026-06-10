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
        'tanggal_dikirim',
        'tanggal_pemesanan'
    ];

    protected $casts = [
        'tanggal_selesai' => 'datetime',
        'tanggal_dikirim' => 'datetime',
        'tanggal_pemesanan' => 'date',
    ];

    protected $appends = [
        'alamat_asal_clean',
        'alamat_tujuan_clean',
        'alamat_asal_coordinate',
        'alamat_tujuan_coordinate'
    ];

    public function getAlamatAsalCleanAttribute()
    {
        if (str_contains($this->alamat_asal, ' @')) {
            return explode(' @', $this->alamat_asal)[0];
        }
        return $this->alamat_asal;
    }

    public function getAlamatTujuanCleanAttribute()
    {
        if (str_contains($this->alamat_tujuan, ' @')) {
            return explode(' @', $this->alamat_tujuan)[0];
        }
        return $this->alamat_tujuan;
    }

    public function getAlamatAsalCoordinateAttribute()
    {
        if (str_contains($this->alamat_asal, ' @')) {
            return explode(' @', $this->alamat_asal)[1];
        }
        return null;
    }

    public function getAlamatTujuanCoordinateAttribute()
    {
        if (str_contains($this->alamat_tujuan, ' @')) {
            return explode(' @', $this->alamat_tujuan)[1];
        }
        return null;
    }

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

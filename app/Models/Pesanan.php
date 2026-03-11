<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sopir;
use App\Models\Kendaraan;

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
        'kendaraan_id'
    ];

    public function sopir()
    {
        return $this->belongsTo(Sopir::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

}

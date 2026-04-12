<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sopir extends Model
{
    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'nama',
        'no_hp',
        'alamat',
        'is_online'
    ];
    
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getKetersediaanAttribute()
    {
        if (!$this->is_online) {
            return 'Offline';
        }

        // Check if there is an active order
        $sedangBertugas = \App\Models\Pesanan::where('sopir_id', $this->id)
            ->where('status', '!=', 'SELESAI')
            ->exists();

        return $sedangBertugas ? 'Sedang Bertugas' : 'Tersedia';
    }
}

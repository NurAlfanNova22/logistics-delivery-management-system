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
        'alamat'
    ];
    
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

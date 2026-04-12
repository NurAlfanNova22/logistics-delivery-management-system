<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    protected $fillable = [
        'pesanan_id',
        'lokasi',
        'lat',
        'lng'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}

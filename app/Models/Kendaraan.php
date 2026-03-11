<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sopir;

class Kendaraan extends Model
{
    protected $fillable = [
        'no_polisi',
        'jenis',
        'merk'
    ];

    public function sopirs()
    {
        return $this->hasMany(Sopir::class);
    }
}
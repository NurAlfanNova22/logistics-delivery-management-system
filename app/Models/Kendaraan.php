<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sopir;

class Kendaraan extends Model
{
    protected $fillable = ['no_polisi','jenis','merk','sopir_id'];
    
    public function sopir()
    {
        return $this->belongsTo(Sopir::class);
    }
}

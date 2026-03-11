<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sopir extends Model
{
    protected $fillable = ['nama','no_hp','alamat'];
    
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}

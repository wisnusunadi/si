<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mproduk extends Model
{
    use HasFactory;

    protected $table = 'm_produk';

    function detailproduk() {
        return $this->hasMany(Produk::class, 'produk_id');
    }

    public function KelompokProduk()
    {
        return $this->belongsTo(KelompokProduk::class, 'kelompok_produk_id');
    }
}

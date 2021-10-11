<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokProduk extends Model
{
    protected $table = 'kelompok_produk';

    public function Produk()
    {
        return $this->hasMany(Produk::class);
    }
}

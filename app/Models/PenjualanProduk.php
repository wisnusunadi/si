<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanProduk extends Model
{
    protected $table = 'penjualan_produk';
    protected $fillable = ['nama', 'harga'];

    public function DetailPenjualanProduk()
    {
        return $this->hasMany(DetailPenjualanProduk::class);
    }
}

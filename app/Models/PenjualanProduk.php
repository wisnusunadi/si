<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanProduk extends Model
{
    protected $table = 'penjualan_produk';
    protected $fillable = ['nama', 'harga'];

    public function DetailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
    public function Produk()
    {
        return $this->belongsToMany(Produk::class, 'detail_penjualan_produk')
            ->withPivot('jumlah');
    }
}

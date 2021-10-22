<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSpa extends Model
{
    protected $table = 'detail_spa';
    protected $fillable = ['spa_id', 'penjualan_produk_id', 'jumlah', 'harga', 'ongkir'];

    public function Spa()
    {
        return $this->belongsTo(Spa::class, 'spa_id');
    }
    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class, 'penjualan_produk_id');
    }
}

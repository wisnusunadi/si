<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSpb extends Model
{
    protected $table = 'detail_spb';
    protected $fillable = ['spb_id', 'penjualan_produk_id', 'jumlah', 'harga', 'ongkir'];

    public function Spb()
    {
        return $this->belongsTo(Spb::class, 'spb_id');
    }
    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class, 'penjualan_produk_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRencanaPenjualan extends Model
{
    protected $table = 'detail_rencana_penjualan';
    protected $fillable = ['rencana_penjualan_id', 'penjualan_produk_id', 'jumlah', 'harga'];


    public function RencanaPenjualan()
    {
        return $this->belongsTo(RencanaPenjualan::class, 'rencana_penjualan_id');
    }
    public function DetailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class, 'penjualan_produk_id');
    }
}

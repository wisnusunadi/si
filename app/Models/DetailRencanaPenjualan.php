<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRencanaPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_rencana_penjualan';

    protected $fillable = ['rencana_penjualan_id', 'penjualan_produk_id', 'jumlah', 'harga', 'updated_at', 'created_at'];

    public function RencanaPenjualan()
    {
        return $this->belongsTo(RencanaPenjualan::class);
    }

    public function DetailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class);
    }
}

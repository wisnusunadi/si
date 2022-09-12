<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEkatalog extends Model
{
    protected $connection = 'erp';
    protected $table = 'detail_ekatalog';
    protected $fillable = ['ekatalog_id', 'penjualan_produk_id', 'jumlah', 'harga', 'ongkir'];

    public function Ekatalog()
    {
        return $this->belongsTo(Ekatalog::class, 'ekatalog_id');
    }
    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class, 'penjualan_produk_id');
    }
}

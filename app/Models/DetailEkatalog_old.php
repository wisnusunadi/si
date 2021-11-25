<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEkatalog extends Model
{
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
    // public function DetailEkatalogProduk()
    // {
    //     return $this->hasMany(DetailEkatalogProduk::class);
    // }

    public function GudangBarangJadi()
    {
        return $this->belongsToMany(GudangBarangJadi::class, 'detail_ekatalog_produk')
            ->withPivot('jumlah');
    }
}

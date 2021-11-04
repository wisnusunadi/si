<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanProduk extends Model
{
    protected $table = 'penjualan_produk';
    protected $fillable = ['nama', 'harga'];

    public function DetailEkatalog()
    {
        return $this->hasMany(DetailEkatalog::class);
    }
    public function DetailSpa()
    {
        return $this->hasMany(DetailSpa::class);
    }
    public function DetailSpb()
    {
        return $this->hasMany(DetailSpb::class);
    }
    public function Produk()
    {
        return $this->belongsToMany(Produk::class, 'detail_penjualan_produk')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
}

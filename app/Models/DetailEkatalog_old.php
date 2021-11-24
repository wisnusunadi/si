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
<<<<<<< HEAD:app/Models/DetailEkatalog_old.php
    // public function DetailEkatalogProduk()
    // {
    //     return $this->hasMany(DetailEkatalogProduk::class);
    // }
=======

>>>>>>> 688feb96d856dcda5093fcf24faa4015090c9bae:app/Models/DetailEkatalog.php
    public function GudangBarangJadi()
    {
        return $this->belongsToMany(GudangBarangJadi::class, 'detail_ekatalog_produk')
            ->withPivot('jumlah');
    }
}

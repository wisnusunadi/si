<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = ['kelompok_produk_id', 'merk', 'tipe', 'nama', 'nama_coo', 'satuan', 'no_akd', 'ket', 'status'];

    public function PenjualanProduk()
    {
        return $this->belongsToMany(PenjualanProduk::class);
    }
    public function GudangBarangJadi()
    {
        return $this->hasMany(GudangBarangJadi::class);
    }
    public function KelompokProduk()
    {
        return $this->belongsTo(KelompokProduk::class, 'kelompok_produk_id');
    }
}

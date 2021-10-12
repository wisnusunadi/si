<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\DetailProduk;

class Produk extends Model
{
    protected $fillable = ['kelompok_produk_id', 'kategori_id', 'merk', 'tipe', 'nama', 'kode_barcode', 'nama_coo', 'no_akd', 'keterangan', 'kalibrasi', 'ppic_id'];

    public function DetailProduk()
    {
        return $this->hasMany(DetailProduk::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEkatalogProduk extends Model
{
    use HasFactory;

    protected $table = 'detail_ekatalog_produk';

    function detailkatalog() {
        return $this->belongsTo(DetailEkatalog::class, 'detail_ekatalog_id');
    }

    function gbj() {
        return $this->belongsTo(GudangBarangJadi::class, 'gudang_barang_jadi_id');
    }
}

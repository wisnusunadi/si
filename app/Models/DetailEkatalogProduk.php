<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEkatalogProduk extends Model
{
<<<<<<< HEAD
    // protected $table = 'detail_ekatalog_produk';
    // protected $fillable = ['detail_ekatalog_id', 'gbj_id', 'jumlah'];

    // public function DetailEkatalog()
    // {
    //     return $this->belongsTo(DetailEkatalog::class, 'detail_ekatalog_id');
    // }
    // public function GudangBarangJadi()
    // {
    //     return $this->belongsTo(GudangBarangJadi::class, 'gbj_id');
    // }
=======
    use HasFactory;

    protected $table = 'detail_ekatalog_produk';

    function detailkatalog() {
        return $this->belongsTo(DetailEkatalog::class, 'detail_ekatalog_id');
    }

    function gbj() {
        return $this->belongsTo(GudangBarangJadi::class, 'gudang_barang_jadi_id');
    }
>>>>>>> e5684c8a068a484366c522da78b46199f5e65b1c
}

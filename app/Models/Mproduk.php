<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mproduk extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'm_produk';
    protected $fillable = [
        'kode',
        'nama'
    ];

    function detailproduk() {
        return $this->hasMany(Produk::class, 'produk_id');
    }

    public function KelompokProduk()
    {
        return $this->belongsTo(KelompokProduk::class, 'kelompok_produk_id');
    }

    public function GudangBarangJadi() {
        return $this->hasMany(GudangBarangJadi::class, 'produk_id');
    }
}

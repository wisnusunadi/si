<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;
use App\Models\NoseriBarangJadi;

class GudangBarangJadi extends Model
{
    use HasFactory;

    protected $table = "gdg_barang_jadi";

    public function noseri()
    {
        return $this->hasMany(NoseriBarangJadi::class, 'gdg_barang_jadi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}

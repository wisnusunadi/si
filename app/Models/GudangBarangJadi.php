<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarangJadi extends Model
{
    protected $table = 'gdg_barang_jadi';
    protected $fillable = ['produk_id', 'variasi', 'stok'];

    public function Produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}

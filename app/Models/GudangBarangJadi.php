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
    protected $fillable = ['produk_id', 'variasi', 'stok', 'ruang'];

    public function noseri()
    {
        return $this->hasMany(NoseriBarangJadi::class, 'gdg_barang_jadi_id');
    }

    function history()
    {
        return $this->hasMany(GudangBarangJadiHis::class, 'gdg_brg_jadi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    function Layout()
    {
        return $this->belongsTo(Layout::class);
    }

     function satuan() {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}

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
<<<<<<< HEAD
    public function DetailEkatalog()
    {
        return $this->belongsToMany(DetailEkatalog::class, 'detail_ekatalog_produk')
            ->withPivot('jumlah');
=======

    function Layout()
    {
        return $this->belongsTo(Layout::class);
    }

     function satuan() {
        return $this->belongsTo(Satuan::class, 'satuan_id');
>>>>>>> e5684c8a068a484366c522da78b46199f5e65b1c
    }
}

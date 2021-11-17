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
    protected $fillable = ['produk_id', 'variasi', 'stok'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function DetailEkatalog()
    {
        return $this->belongsToMany(DetailEkatalog::class, 'detail_ekatalog_produk')
            ->withPivot('jumlah');
    }
}

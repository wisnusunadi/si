<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarangJadiHis extends Model
{
    use HasFactory;

    protected $table = 'gdg_barang_jadi_his';
    protected $fillable = ['stok', 'jenis'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    function Layout(){
        return $this->belongsTo(Layout::class);
    }

    function from() {
        return $this->belongsTo(Divisi::class, 'dari');
    }

    function to() {
        return $this->belongsTo(Divisi::class, 'ke');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFProduksi extends Model
{
    use HasFactory;

    // protected $table = 't_tfbj';

    protected $table = 't_gbj';

    protected $fillable = ['ke', 'deskripsi'];

    function detail() {
        return $this->hasMany(TFProduksiDetail::class, 't_gbj_id');
    }

    function his() {
        return $this->hasMany(TFProduksiHis::class, 'tfbj_id');
    }

    function divisi() {
        return $this->belongsTo(Divisi::class, 'ke');
    }

    function pesanan() {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}

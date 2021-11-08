<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFProduksi extends Model
{
    use HasFactory;

    protected $table = 't_tfbj';

    function detail_tf() {
        return $this->hasMany(TFProduksiDetail::class, 'tfbj_id');
    }

    function his_tf() {
        return $this->hasMany(TFProduksiHis::class, 'tfbj_id');
    }

    function divisi() {
        return $this->belongsTo(Divisi::class, 'ke');
    }
}

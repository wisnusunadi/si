<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriTGbj extends Model
{
    use HasFactory;

    protected $table = "t_gbj_noseri";

    function detail() {
        return $this->belongsTo(TFProduksiDetail::class, 't_gbj_detail_id');
    }

    function layout() {
        return $this->belongsTo(Layout::class, 'layout_id');
    }

    function seri() {
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_id');
    }
}

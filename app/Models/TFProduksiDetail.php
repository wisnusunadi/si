<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFProduksiDetail extends Model
{
    use HasFactory;

    protected $table = 't_gbj_detail';

    function header()
    {
        return $this->belongsTo(TFProduksi::class, 't_gbj_id');
    }

    function produk()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gdg_brg_jadi_id');
    }
}

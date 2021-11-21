<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFProduksiHis extends Model
{
    use HasFactory;

    protected $table = 't_tfbj_his';

    function header() {
        return $this->hasOne(TFProduksi::class, 'tfbj_id');
    }
}

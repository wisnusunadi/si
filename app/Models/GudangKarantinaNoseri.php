<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangKarantinaNoseri extends Model
{
    use HasFactory;

    protected $table = 't_gk_noseri';

    function detail() {
        return $this->belongsTo(GudangKarantinaDetail::class, 'gk_detail_id');
    }
}

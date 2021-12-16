<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriKeluarGK extends Model
{
    use HasFactory;

    protected $table = 't_gk_noseri_out';

    protected $fillable = ['gk_detail_id', 'noseri_id'];

    function detail() {
        return $this->belongsTo(GudangKarantinaDetail::class, 'gk_detail_id');
    }

    function noseri() {
        return $this->belongsTo(GudangKarantinaNoseri::class, 'noseri_id');
    }
}

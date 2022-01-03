<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangKarantinaDetail extends Model
{
    use HasFactory;

    protected $table = 't_gk_detail';

    protected $fillable = ['gk_id', 'gbj_id', 'sparepart_id', 'qty_unit', 'qty_spr', 'is_draft', 'is_keluar'];

    function units() {
        return $this->belongsTo(GudangBarangJadi::class, 'gbj_id');
    }

    function sparepart() {
        return $this->belongsTo(SparepartGudang::class, 'sparepart_id');
    }

    function header()
    {
        return $this->belongsTo(GudangKarantina::class, 'gk_id');
    }

    function noseri() {
        return $this->hasMany(GudangKarantinaNoseri::class, 'gk_detail_id');
    }

}

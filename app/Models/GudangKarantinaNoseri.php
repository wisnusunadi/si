<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangKarantinaNoseri extends Model
{
    use HasFactory;

    protected $table = 't_gk_noseri';

    protected $fillable = ['gk_detail_id', 'noseri', 'remark', 'tk_kerusakan', 'is_draft', 'is_keluar', 'is_ready', 'out_noseri'];

    function detail() {
        return $this->belongsTo(GudangKarantinaDetail::class, 'gk_detail_id');
    }

    function layout() {
        return $this->belongsTo(Layout::class, 'layout_id');
    }
}

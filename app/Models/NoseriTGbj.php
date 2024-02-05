<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriTGbj extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "t_gbj_noseri";

    protected $fillable = ['created_at', 'updated_at', 't_gbj_detail_id', 'noseri_id', 'status_id', 'layout_id', 'state_id', 'jenis', 'waktu_tf', 'transfer_by', 'created_by'];

    function detail()
    {
        return $this->belongsTo(TFProduksiDetail::class, 't_gbj_detail_id');
    }
    function layout()
    {
        return $this->belongsTo(Layout::class, 'layout_id');
    }
    function seri()
    {
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_id');
    }
    function NoseriBarangJadi()
    {
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_id');
    }
    function NoseriDetailPesanan()
    {
        return $this->hasOne(NoseriDetailPesanan::class, 't_tfbj_noseri_id');
    }

}

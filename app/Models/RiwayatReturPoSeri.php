<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatReturPoSeri extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "riwayat_retur_po_seri";
    protected $fillable = ['detail_riwayat_retur_prd_id', 't_tfbj_noseri_id','noseri_id','status','noseri_logistik_id'];
}

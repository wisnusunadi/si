<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBatalPoSeri extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "riwayat_batal_po_seri";
    protected $fillable = ['detail_riwayat_batal_prd_id', 't_tfbj_noseri_id','noseri_id','status','posisi'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBatalPoPrd extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "riwayat_batal_po_prd";
    protected $fillable = ['detail_pesanan_id', 'jumlah','status'];

}

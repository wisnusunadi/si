<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBatalPoPart extends Model
{
    use HasFactory;
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "riwayat_batal_po_part";
    protected $fillable = ['detail_pesanan_part_id', 'jumlah','status'];
}

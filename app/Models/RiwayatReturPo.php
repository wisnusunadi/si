<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatReturPo extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "riwayat_retur_po";
    protected $fillable = ['id', 'pesanan_id','no_retur'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatReturPoPrd extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "riwayat_retur_po_prd";
    protected $fillable = ['detail_riwayat_retur_paket_id','gudang_barang_jadi_id','detail_pesanan_produk_id'];

}

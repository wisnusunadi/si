<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBatalPoPaket extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "riwayat_batal_po_paket";
    protected $fillable = ['detail_pesanan_id', 'jumlah','riwayat_batal_po_id'];

    public function DetailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class, 'detail_pesanan_id');
    }
}

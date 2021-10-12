<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bppb extends Model
{
    protected $fillable = ['detail_produk_id', 'versi_bom', 'no_bppb', 'tanggal_bppb', 'divisi_id', 'jumlah'];

    public function DetailProduk()
    {
        return $this->belongsTo(DetailProduk::class);
    }

    public function Divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}

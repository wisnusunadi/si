<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatGagalKalibrasi extends Model
{
    use HasFactory;
    protected $table = 'riwayat_gagal_kalibrasi';
    protected $fillable = ['detail_pesanan_produk_id','isi'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatAktifPeriode extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'riwayat_aktif_periode';
    protected $fillable = ['user','isi','status','tgl_konfirmasi'];
}

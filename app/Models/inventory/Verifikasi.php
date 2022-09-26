<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi';
    protected $connection = 'inventory';
    protected $fillable = ['serial_number_id', 'pengendalian', 'keputusan', 'hasil_fisik', 'hasil_fungsi', 'keterangan', 'gambar', 'tgl_perawatan', 'jadwal_perawatan', 'created_by'];

}

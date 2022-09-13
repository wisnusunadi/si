<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarJadwalPerakitan extends Model
{
    protected $connection = 'erp';
    protected $table = "komentar_jadwal_perakitan";
    protected $fillable = ['tanggal_permintaan', 'tanggal_hasil', 'state', 'status', 'hasil', 'komentar'];
}

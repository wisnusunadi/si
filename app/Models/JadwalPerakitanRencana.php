<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPerakitanRencana extends Model
{
    protected $table = 'jadwal_perakitan_rencana';
    protected $fillable = ['jadwal_perakitan_id', 'tanggal_mulai', 'tanggal_selesai'];

    public function JadwalPerakitan()
    {
        return $this->belongsTo(JadwalPerakitan::class, 'jadwal_perakitan_id');
    }
}

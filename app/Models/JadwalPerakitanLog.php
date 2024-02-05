<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPerakitanLog extends Model
{
    protected $connection = 'erp';
    protected $table = 'jadwal_perakitan_log';
    protected $fillable = ['jadwal_perakitan_id', 'tanggal_mulai', 'tanggal_selesai', 'tanggal_mulai_baru', 'tanggal_selesai_baru'];

    public function JadwalPerakitan()
    {
        return $this->belongsTo(JadwalPerakitan::class, 'jadwal_perakitan_id');
    }
}

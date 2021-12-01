<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalRakitNoseri extends Model
{
    use HasFactory;

    protected $table = "jadwal_rakit_noseri";

    function header()
    {
        return $this->belongsTo(JadwalPerakitan::class, 'jadwal_id');
    }
}

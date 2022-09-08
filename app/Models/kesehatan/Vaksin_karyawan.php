<?php

namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Vaksin_karyawan extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'vaksin_karyawans';
    protected $fillable = ['karyawan_id', 'tgl', 'dosis', 'tahap'];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}

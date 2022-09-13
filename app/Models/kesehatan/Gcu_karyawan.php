<?php

namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Gcu_karyawan extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'gcu_karyawans';
    protected $primaryKey = 'id';
    protected $fillable = ['karyawan_id', 'tgl_cek', 'glukosa', 'kolesterol', 'asam_urat', 'keterangan'];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}

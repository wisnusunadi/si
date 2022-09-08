<?php

namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Kesehatan_mingguan_rapid extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'kesehatan_mingguan_rapids';
    protected $fillable = ['karyawan_id', 'pemeriksa_id', 'tgl_cek', 'hasil', 'jenis', 'keterangan', 'file'];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class,'karyawan_id');
    }
    public function Pemeriksa()
    {
        return $this->belongsTo(Karyawan::class, 'pemeriksa_id');
    }
}

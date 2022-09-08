<?php

namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Kesehatan_mingguan_tensi extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'kesehatan_mingguan_tensis';
    protected $primaryKey = 'id';
    protected $fillable = ['karyawan_id', 'tgl_cek', 'sistolik', 'diastolik', 'keterangan'];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class,'karyawan_id');
    }
}

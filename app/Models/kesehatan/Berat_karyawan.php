<?php

namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Berat_karyawan extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'berat_karyawans';
    protected $fillable = ['karyawan_id', 'tgl_cek', 'berat', 'lemak', 'kandungan_air', 'otot', 'tulang', 'kalori', 'suhu', 'spo2', 'pr', 'sistolik', 'diastolik', 'keterangan'];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class,'karyawan_id');
    }
}

<?php

namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Kesehatan_tahunan extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'kesehatan_tahunans';
    protected $primaryKey = 'id';
    protected $fillable = ['karyawan_id', 'pemeriksa_id', 'tgl_cek', 'mata_kiri', 'mata_kanan', 'keterangan'];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
    public function Pemeriksa()
    {
        return $this->belongsTo(Karyawan::class, 'pemeriksa_id');
    }
}

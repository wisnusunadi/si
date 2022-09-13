<?php

namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Riwayat_penyakit extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'riwayat_penyakits';
    protected $fillable = ['karyawan_id', 'nama', 'jenis', 'kriteria', 'keterangan'];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}

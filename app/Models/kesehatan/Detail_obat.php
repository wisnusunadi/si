<?php

namespace App\Models\kesehatan;

use Illuminate\Database\Eloquent\Model;

class Detail_obat extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'detail_obats';
    protected $fillable = ['karyawan_sakit_id', 'obat_id', 'jumlah', 'aturan', 'konsumsi'];

    public function Obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function Karyawan_sakit()
    {
        return $this->belongsTo(Karyawan_sakit::class, 'karyawan_sakit_id');
    }
}

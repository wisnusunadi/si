<?php

namespace App\Models\kesehatan;


use Illuminate\Database\Eloquent\Model;

class Riwayat_stok_obat extends Model
{
    protected $connection = 'kesehatan';
    protected $table = 'riwayat_stok_obats';
    protected $primaryKey = 'id';
    protected $fillable = ['obat_id', 'tgl_pembelian', 'stok', 'keterangan'];

    public function Obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}

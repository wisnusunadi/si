<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBatalPo extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "riwayat_batal_po";
    protected $fillable = ['id', 'pesanan_id','ket'];

    public function RiwayatBatalPoPaket()
    {
        return $this->hasMany(RiwayatBatalPoPaket::class);
    }
}

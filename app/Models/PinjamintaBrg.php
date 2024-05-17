<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamintaBrg extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'pinjaminta_brg';
    protected $fillable = ['no','jenis', 'divisi_id', 'tgl_kebutuhan', 'tgl_kembali', 'ket','no_permintaan','status_gdg','status_atasan','status'];

    public function PinjamintaBrgDetail()
    {
        return $this->hasMany(PinjamintaBrgDetail::class);
    }
}

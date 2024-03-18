<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'm_sparepart';
    protected $fillable = [
        'nama',
        'kelompok_produk_id',
        'kode',
        'jenis'
    ];

    function kategori()
    {
        return $this->belongsTo(KelompokProduk::class, 'kelompok_produk_id');
    }

    public function DetailPesananPart()
    {
        return $this->hasMany(DetailPesananPart::class, 'm_sparepart_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamintaBrgDetail extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'pinjaminta_brg_detail';
    protected $fillable = ['pinjaminta_brg_id', 'produk_id', 'jumlah'];

    public function Produk()
    {
        return $this->belongsTo(Produk::class,'produk_id');
    }

}

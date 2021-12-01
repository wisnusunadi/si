<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLogistik extends Model
{
    protected $table = 'detail_logistik';
    protected $fillable = ['logistik_id', 'detail_pesanan_id'];

    public function Logistik()
    {
        return $this->belongsTo(Logistik::class, 'logistik_id');
    }
    public function DetailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class, 'detail_pesanan_id');
    }
    public function NoseriDetailLogistik()
    {
        return $this->hasMany(NoseriDetailLogistik::class);
    }
}

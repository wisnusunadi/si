<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingPesananPart extends Model
{
    // use HasFactory;
    protected $connection = 'erp';
    protected $table = 'outgoing_pesanan_part';
    protected $fillable = ['detail_pesanan_part_id', 'detail_logistik_part_id', 'tanggal_uji', 'jumlah_ok', 'jumlah_nok', 'is_ready'];

    public function DetailPesananPart()
    {
        return $this->belongsTo(DetailPesananPart::class, 'detail_pesanan_part_id');
    }

    public function DetailLogistikPart()
    {
        return $this->belongsTo(DetailLogistikPart::class, 'detail_logistik_part_id');
    }

    // public function Sparepart()
    // {
    //     return $this->belongsTo(Sparepart::class, 'm_sparepart_id');
    // }
    // public function DetailLogistikPart()
    // {
    //     return $this->hasOne(DetailLogistikPart::class);
    // }
    // public function OutgoingPesananPart()
    // {
    //     return $this->hasMany(OutgoingPesananPart::class);
    // }
}

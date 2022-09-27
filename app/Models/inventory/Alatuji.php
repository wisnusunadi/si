<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alatuji extends Model
{
    use HasFactory;

    protected $table = 'alatuji';
    protected $connection = 'erp_kalibrasi';
    protected $fillable = ['klasifikasi_id', 'kd_alatuji', 'nm_alatuji', 'gbr_alatuji', 'desk_alatuji', 'sop_alatuji', 'manual_alatuji', 'stok_alatuji', 'satuan_alatuji', 'created_by'];

    function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'klasifikasi_id');
    }

    function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_alatuji');
    }
}

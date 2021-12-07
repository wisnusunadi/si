<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriCoo extends Model
{
    protected $table = 'noseri_coo';
    protected $fillable = ['nama', 'jabatan', 'noseri_logistik_id'];

    public function Noserilogistik()
    {
        return $this->belongsTo(NoseriDetailLogistik::class, 'noseri_logistik_id');
    }
}

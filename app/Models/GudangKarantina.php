<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangKarantina extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 't_gk';

    function detail() {
        return $this->belongsToMany(GudangKarantinaDetail::class, 'gk_id');
    }
    function from() {
        return $this->belongsTo(Divisi::class, 'dari');
    }

    function to() {
        return $this->belongsTo(Divisi::class, 'ke');
    }


}

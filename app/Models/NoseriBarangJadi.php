<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriBarangJadi extends Model
{
    use HasFactory;

    protected $table = "noseri_barang_jadi";

    function from() {
        return $this->belongsTo(Divisi::class, 'dari');
    }

    function to() {
        return $this->belongsTo(Divisi::class, 'ke');
    }

    function gudang() {
        return $this->belongsTo(GudangBarangJadi::class, 'gdg_barang_jadi_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartGudang extends Model
{
    use HasFactory;

    protected $table = 'm_gs';

    // protected $fillable = ['kelompok', 'kode', 'nama', 'stok'];

    function Spare() {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }

    function his() {
        return $this->hasMany(SparepartHis::class, 'gs_id');
    }

    function Layout() {
        return $this->belongsTo(Layout::class);
    }

}

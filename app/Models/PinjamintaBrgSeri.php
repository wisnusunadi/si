<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamintaBrgSeri extends Model
{
    use HasFactory;
    protected $table = 'pinjaminta_brg_detail';
    protected $fillable = ['pinjaminta_brg_detail_id', 'noseri_id', 'status','tgl_kembali'];
}

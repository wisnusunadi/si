<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamintaBrgLog extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'pinjaminta_brg_riwayat';
    protected $fillable = ['pinjaminta_brg_id','isi','ket'];

}

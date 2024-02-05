<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTf extends Model
{
    use HasFactory;
    protected $table = 'riwayat_tf';
    protected $fillable = ['dari','ke','jenis','isi'];
}

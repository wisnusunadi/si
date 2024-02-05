<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeriGanti extends Model
{
    use HasFactory;
    protected $table = 'seri_ganti';
    protected $fillable = ['detail_pesanan_produk_id','isi', 'state'];
}

<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $connection = 'inventory';
    protected $guarded = ['id_peminjaman', 'created_at', 'updated_at'];
    protected $primaryKey = 'id_peminjaman';
}

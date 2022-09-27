<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class merk extends Model
{
    use HasFactory;
    protected $table = 'merk';
    protected $connection = 'erp_kalibrasi';
    protected $guarded = ['id_merk'];
}

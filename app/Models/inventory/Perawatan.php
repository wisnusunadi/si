<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    use HasFactory;

    protected $table = 'perawatan';
    protected $connection = 'erp_kalibrasi';
    protected $guarded = ['id_perawatan', 'created_at', 'updated_at'];
    protected  $primaryKey = 'id_perawatan';

}

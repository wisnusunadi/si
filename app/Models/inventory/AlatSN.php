<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlatSN extends Model
{
    use HasFactory;

    protected $table = 'alatuji_sn';
    protected $connection = 'inventory';
    protected $guarded = ['id_serial_number', 'created_at', 'updated_at'];
    protected $primaryKey = 'id_serial_number';
}

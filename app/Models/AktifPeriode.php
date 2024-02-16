<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktifPeriode extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'aktif_periode';
    protected $fillable = ['tahun'];

}

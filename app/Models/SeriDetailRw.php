<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeriDetailRw extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'seri_detal_rw';
    protected $fillable = ['jadwal_id','noseri_id','noseri','isi'];
}

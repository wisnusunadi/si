<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartGudangPartEng extends Model
{
    protected $connection = 'erp';
    protected $fillable = ['kode_gudang', 'kode_eng'];
}

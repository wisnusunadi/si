<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetiRw extends Model
{
    protected $connection = 'erp';
    protected $table = 'peti_rw';
    protected $fillable = ['no_urut','noseri_id','noseri','packer'];

}

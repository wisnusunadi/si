<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogistikDraft extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'logistik_draft';
    protected $fillable = ['pesanan_id','sj','isi'];

}

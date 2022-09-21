<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_log';
    protected $fillable = ['tipe', 'subjek', 'response', 'user_id'];
    protected $connection = 'erp';

    function userid()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

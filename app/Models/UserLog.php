<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $table = 'user_log';

    protected $fillable = ['user_id', 'user_nama', 'subjek', 'tabel', 'keterangan', 'aksi'];

    function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

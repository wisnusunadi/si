<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSurat extends Model
{
    use HasFactory;

    protected $connection = 'erp';
    protected $table = 't_log_surat';

    protected $fillable = ['pesanan_id', 'transfer_by', 'check_by', 'terima_by'];

    function transfer() {
        return $this->belongsTo(User::class, 'transfer_by');
    }

    function check() {
        return $this->belongsTo(User::class, 'check_by');
    }

    function terima() {
        return $this->belongsTo(User::class, 'terima_by');
    }
}

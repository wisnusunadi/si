<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $table = 'm_state';
    protected $fillable = ['nama', 'jenis_id', 'is_aktif'];

    public function Logistik()
    {
        return $this->hasMany(Logistik::class, 'status_id');
    }

    public function Pesanan()
    {
        return $this->hasMany(Pesanan::class, 'log_id');
    }
}

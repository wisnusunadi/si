<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekspedisi extends Model
{
    protected $table = 'ekspedisi';
    protected $fillable = ['nama', 'alamat', 'email', 'telp', 'jalur', 'ket'];

    public function Provinsi()
    {
        return $this->belongsToMany(Provinsi::class, 'ekspedisi_provinsi');
    }
    public function JalurEkspedisi()
    {
        return $this->belongsToMany(JalurEkspedisi::class, 'ekspedisi_jalur_ekspedisi');
    }
}

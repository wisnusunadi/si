<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// models
use App\Models\Produk;

class Event extends Model
{
    protected $table = 'jadwal_prakitan';
    protected $fillable = ['produk_id', 'jumlah', 'tanggal_mulai', 'tanggal_selesai', 'status', 'warna', 'konfirmasi', 'proses_persetujuan'];

    public function Produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}

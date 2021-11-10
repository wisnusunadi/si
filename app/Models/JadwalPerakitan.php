<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// models
use App\Models\GudangBarangJadi;
use App\Models\Status;
use App\Models\State;

class JadwalPerakitan extends Model
{
    protected $table = 'jadwal_perakitan';
    protected $fillable = ['id', 'produk_id', 'jumlah', 'tanggal_mulai', 'tanggal_selesai', 'status', 'state', 'konfirmasi', 'warna'];

    public function Produk()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'produk_id');
    }

    public function Status()
    {
        return $this->belongsTo(Status::class, 'status');
    }

    public function State()
    {
        return $this->belongsTo(State::class, 'state');
    }
}

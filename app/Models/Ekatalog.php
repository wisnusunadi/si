<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekatalog extends Model
{
    protected $table = 'ekatalog';
    protected $fillable = ['customer_id', 'pesanan_id', 'no_paket', 'deskripsi', 'instansi', 'satuan', 'status', 'tgl_kontrak', 'tgl_buat', 'ket'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function DetailEkatalog()
    {
        return $this->hasMany(DetailEkatalog::class);
    }
    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}

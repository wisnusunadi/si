<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekatalog extends Model
{
    protected $connection = 'erp';
    protected $table = 'ekatalog';
    protected $fillable = ['customer_id', 'provinsi_id', 'pesanan_id', 'no_paket', 'no_urut', 'deskripsi', 'instansi', 'alamat', 'satuan', 'status', 'tgl_kontrak', 'tgl_buat', 'tgl_edit', 'ket', 'log'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
    public function Provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }
}

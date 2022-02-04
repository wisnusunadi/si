<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $fillable = ['id_provinsi', 'nama', 'telp', 'email', 'alamat', 'npwp', 'ktp', 'batas', 'pic', 'ket'];

    public function Spa()
    {
        return $this->hasMany(Spa::class);
    }
    public function Spb()
    {
        return $this->hasMany(Spb::class);
    }
    public function Ekatalog()
    {
        return $this->hasMany(Ekatalog::class);
    }
    public function Provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi');
    }

    public function RencanaPenjualan()
    {
        return $this->hasMany(RencanaPenjualan::class);
    }
}

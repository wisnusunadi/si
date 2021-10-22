<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $fillable = ['nama', 'telp', 'alamat', 'npwp', 'ket'];

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
}

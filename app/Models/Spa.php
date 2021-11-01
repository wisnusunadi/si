<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spa extends Model
{
    protected $table = 'spa';
    protected $fillable = ['customer_id', 'pesanan_id', 'status', 'ket'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function DetailSpa()
    {
        return $this->hasMany(DetailSpa::class);
    }
    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}

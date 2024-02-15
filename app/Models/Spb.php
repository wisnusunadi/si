<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spb extends Model
{
    protected $connection = 'erp';
    protected $table = 'spb';
    protected $fillable = ['customer_id', 'pesanan_id', 'status', 'ket', 'log','created_at','updated_at'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}

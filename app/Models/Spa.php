<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spa extends Model
{
    protected $connection = 'erp';
    protected $table = 'spa';
    protected $fillable = ['customer_id', 'pesanan_id', 'ket', 'log', 'created_at', 'updated_at'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeLab extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'kode_lab';
    public $timestamps = false;

    protected $fillable = ['kode','nama'];

    public function Produk()
    {
        return $this->hasMany(Produk::class);
    }
}

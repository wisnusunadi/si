<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPemilik extends Model
{
    use HasFactory;

    protected $connection = 'erp';
    protected $table = 'jenis_pemilik';
    protected $fillable = ['kode','nama'];
    public $timestamps = false;

    public function UjiLab()
    {
        return $this->hasMany(UjiLab::class);
    }
}

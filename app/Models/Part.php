<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// models
use App\Models\PartGudangPartEng;

class Part extends Model
{
    use HasFactory;

    protected $connection = 'erp';
    protected $primaryKey = "kode";
    protected $fillable = ['nama', 'kode', 'jumlah', 'foto'];

    protected $keyType = "string";
    public $incrementing = false;

    public function PartGudangPartEng()
    {
        return $this->hasMany(PartGudangPartEng::class, 'kode_gudang');
    }
}

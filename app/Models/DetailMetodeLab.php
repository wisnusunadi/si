<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMetodeLab extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_metode_lab';
    public $timestamps = false;
    protected $fillable = ['metode_lab_id','ruang'];

    public function UjiLabDetail()
    {
        return $this->hasMany(UjiLabDetail::class,'metode_id');
    }
    public function MetodeLab()
    {
        return $this->belongsTo(MetodeLab::class,'metode_lab_id');
    }
    public function RuangKalibrasi()
    {
        return $this->belongsTo(RuangKalibrasi::class,'ruang');
    }
}

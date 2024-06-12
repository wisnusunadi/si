<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiMeet extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'lokasi_meeting';
    protected $fillable = ['nama'];
}

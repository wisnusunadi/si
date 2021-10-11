<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\DivisiInventory;

class Divisi extends Model
{
    protected $fillable = ['nama', 'kode'];
}

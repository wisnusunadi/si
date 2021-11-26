<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Divisi extends Model
{
    protected $table = "divisi";
    protected $fillable = ['nama', 'kode'];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
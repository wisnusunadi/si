<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\State;
use App\Models\Status;

class KomentarPpic extends Model
{
    use HasFactory;

    protected $table = "komentar_ppic";
    protected $fillable = ['tanggal', 'state', 'status', 'komentar'];

    public function Status()
    {
        return $this->belongsTo(Status::class, 'status');
    }

    public function State()
    {
        return $this->belongsTo(State::class, 'state');
    }
}

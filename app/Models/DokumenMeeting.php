<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenMeeting extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'dokumen_meeting';
    protected $fillable = ['meeting_id', 'nama', 'original'];

    public function JadwalMeeting()
    {
        return $this->belongsTo(JadwalMeeting::class, 'meeting_id');
    }
}

<?php

namespace App\Models;

use App\Models\kesehatan\Karyawan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilNotulen extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'hasil_notulen';
    protected $fillable = ['urutan', 'meeting_id', 'pic_id', 'uraian', 'hasil', 'verif_id', 'ket', 'checked_at'];

    public function JadwalMeeting()
    {
        return $this->belongsTo(JadwalMeeting::class, 'meeting_id');
    }

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'pic_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftGbjNoSeri extends Model
{
    use HasFactory;

    protected $table = 't_draft_gbj_noseri';

    function draft() {
        return $this->hasMany(DraftGBJ::class, 'draft_gbj_id');
    }

    function layout() {
        return $this->belongsTo(Layout::class, 'layout_id');
    }
}

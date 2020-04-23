<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingDetail extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function programmeRevision()
    {
        return $this->belongsTo(ProgrammeRevision::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

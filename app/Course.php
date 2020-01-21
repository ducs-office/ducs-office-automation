<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'name', 'type'];

    public function programme_revisions()
    {
        return $this->belongsToMany(ProgrammeRevision::class);
    }

    public function revisions()
    {
        return $this->hasMany(CourseRevision::class);
    }
}

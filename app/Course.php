<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'name', 'type'];

    public function programmeRevisions()
    {
        return $this->belongsToMany(ProgrammeRevision::class);
    }

    public function revisions()
    {
        return $this->hasMany(CourseRevision::class);
    }
}

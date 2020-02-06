<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PastTeachingDetail extends Model
{
    protected $guarded = [];

    public function course_programme_revision()
    {
        return $this->hasOne(CourseProgrammeRevision::class, 'id', 'course_programme_revision_id');
    }
}

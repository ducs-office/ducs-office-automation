<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseProgrammeRevision extends Pivot
{
    public $timestamps = false;

    public function programmeRevision()
    {
        return $this->belongsTo(ProgrammeRevision::class, 'programme_revision_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
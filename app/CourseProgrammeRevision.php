<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseProgrammeRevision extends Pivot
{
    public function programme_revision()
    {
        return $this->belongsTo(ProgrammeRevision::class, 'programme_revision_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


    public function programme_course_set()
    {
        return [
            'programme' => $this->programme_revision->programme,
            'course' => $this->course
        ];
    }
}

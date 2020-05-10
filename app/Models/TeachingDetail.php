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

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function toTeachingRecord()
    {
        return ['valid_from' => TeachingRecord::getStartDate()]
            + $this->only(['teacher_id', 'programme_revision_id', 'course_id', 'semester'])
            + $this->teacher->only(['college_id', 'status', 'designation']);
    }
}

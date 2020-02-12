<?php

namespace App;

use App\PastTeachingDetail;
use Illuminate\Database\Eloquent\Model;

class PastTeachersProfile extends Model
{
    protected $guarded = [''];

    protected $dates = ['valid_from'];

    public function past_teaching_details()
    {
        return $this->belongsToMany(CourseProgrammeRevision::class, 'past_teaching_details', 'past_teachers_profile_id', 'course_programme_revision_id');
    }

    public function college()
    {
        return $this->belongsTo('App\College');
    }

    public function getDesignation()
    {
        return config('options.teachers.designations')[$this->designation];
    }
}

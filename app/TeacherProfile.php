<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $primaryKey = 'teacher_id';

    protected $fillable = [
        'phone_no',
        'address',
        'designation',
        'college_id',
        'teacher_id',
    ];

    protected $table = 'teachers_profile';

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    public function college()
    {
        return $this->belongsTo('App\College');
    }

    public function teaching_details()
    {
        return $this->belongsToMany(CourseProgrammeRevision::class, 'teaching_details', 'teacher_id', 'course_programme_revision_id');
    }

    public function profile_picture()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function getDesignation()
    {
        return config('options.teachers.designations')[$this->designation] ?? 'Unknown';
    }
    
    public function past_profiles()
    {
        return $this->hasMany(PastTeachersProfile::class, 'teacher_id');
    }
}

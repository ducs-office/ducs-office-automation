<?php

namespace App\Models;

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
        return $this->belongsTo(Teacher::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function teachingDetails()
    {
        return $this->hasMany(TeachingDetail::class, 'teacher_id');
    }

    public function profilePicture()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function getDesignation()
    {
        return config('options.teachers.designations')[$this->designation] ?? 'Unknown';
    }

    public function isCompleted()
    {
        return $this->designation
            && $this->college_id
            && $this->teachingDetails()->count() > 0;
    }
}

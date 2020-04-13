<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\TeacherStatus;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $table = 'teachers_profile';
    protected $primaryKey = 'teacher_id';

    protected $fillable = [
        'phone_no',
        'address',
        'designation',
        'college_id',
        'teacher_id',
    ];

    protected $casts = [
        'designation' => CustomType::class . ':' . TeacherStatus::class,
    ];

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

    public function isCompleted()
    {
        return $this->designation
            && $this->college_id
            && $this->teachingDetails()->count() > 0;
    }
}

<?php

namespace App;

use Illuminate\Foundation\Auth\User;

class Teacher extends User
{
    protected $guarded = [];

    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();

        static::created(function (Teacher $teacher) {
            $teacher->profile()->create();

            return $teacher;
        });
    }
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function profile()
    {
        return $this->hasOne(TeacherProfile::class, 'teacher_id');
    }

    public function past_profiles()
    {
        return $this->hasMany(PastTeachersProfile::class, 'teacher_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Teacher extends User
{
    use Notifiable;

    protected $guarded = [];

    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();

        static::created(static function (Teacher $teacher) {
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

    public function teachingRecords()
    {
        return $this->hasMany(TeachingRecord::class, 'teacher_id');
    }
}

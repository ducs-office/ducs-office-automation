<?php

namespace App;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

class Teacher extends User
{
    use Notifiable;

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

    public function scopeApplyFilter($query, $filters)
    {
        if (isset($filters['course_id'])) {
            $query->whereHas('past_profiles.past_teaching_details.course', function (Builder $q) use ($filters) {
                $q->where('id', $filters['course_id']);
            });
        }

        if (isset($filters['valid_from'])) {
            $query->whereHas('past_profiles', function (Builder $q) use ($filters) {
                $q->where('valid_from', '>=', $filters['valid_from']);
            });
        }
    }
}

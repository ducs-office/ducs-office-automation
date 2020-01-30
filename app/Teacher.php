<?php

namespace App;

use Illuminate\Foundation\Auth\User;

class Teacher extends User
{
    protected $guarded = [];

    protected $hidden = ['password'];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function profile()
    {
        return $this->hasOne('App\TeacherProfile');
    }
}

<?php

namespace App;

use Illuminate\Foundation\Auth\User;

class CollegeTeacher extends User
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    protected $hidden = ['password'];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

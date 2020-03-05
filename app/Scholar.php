<?php

namespace App;

use App\ScholarProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Scholar extends User
{
    protected $hidden = ['password'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_no',
        'address',
        'category',
        'admission_via',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function profilePicture()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function advisors()
    {
        return $this->hasMany(Advisor::class, 'scholar_id');
    }

    public function advisoryCommittee()
    {
        return $this->advisors()->where('type', 'A');
    }

    public function coSupervisors()
    {
        return $this->advisors()->where('type', 'C');
    }
}

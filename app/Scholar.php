<?php

namespace App;

use App\ScholarProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Scholar extends User
{
    protected $guarded = [];

    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();

        static::created(static function (Scholar $scholar) {
            $scholar->profile()->create();

            return $scholar;
        });
    }

    public function profile()
    {
        return $this->hasOne(ScholarProfile::class, 'scholar_id');
    }

    public function supervisorProfile()
    {
        return $this->belongsTo(SupervisorProfile::class);
    }

    public function supervisor()
    {
        return $this->supervisorProfile->supervisor();
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

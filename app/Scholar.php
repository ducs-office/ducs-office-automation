<?php

namespace App;

use App\ScholarProfile;
use App\SupervisorProfile;
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
        'supervisor_profile_id',
        'gender',
        'research_area',
        'enrollment_date',
        'advisory_committee',
        'co_supervisors',
    ];

    protected $casts = [
        'advisory_committee' => 'array',
        'co_supervisors' => 'array',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function profilePicture()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function supervisorProfile()
    {
        return $this->belongsTo(SupervisorProfile::class);
    }

    public function supervisor()
    {
        return $this->supervisorProfile->supervisor();
    }
}

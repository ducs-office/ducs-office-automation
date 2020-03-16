<?php

namespace App;

use App\AcademicDetail;
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

    public static function boot()
    {
        parent::boot();

        static::created(static function ($scholar) {
            $scholar->courseworks()->attach(PhdCourse::core()->get());
        });
    }

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

    public function academicDetails()
    {
        return $this->hasMany(AcademicDetail::class);
    }

    public function publications()
    {
        return $this->academicDetails()->where('type', 'publication');
    }

    public function presentations()
    {
        return $this->academicDetails()->where('type', 'presentation');
    }

    public function courseworks()
    {
        return $this->belongsToMany(PhdCourse::class)
            ->withPivot(['completed_at'])
            ->using(ScholarCourseworkPivot::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}

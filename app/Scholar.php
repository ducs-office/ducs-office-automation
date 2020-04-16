<?php

namespace App;

use App\AcademicDetail;
use App\Cosupervisor;
use App\Publication;
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
        'education',
    ];

    protected $casts = [
        'advisory_committee' => 'array',
        'education' => 'array',
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

    public function cosupervisors()
    {
        return $this->belongsToMany(Cosupervisor::class, 'cosupervisor_scholar_table');
    }

    public function publications()
    {
        return $this->morphMany(Publication::class, 'main_author');
    }

    public function journals()
    {
        return $this->publications()->where('type', 'journal')->orderBy('date', 'DESC');
    }

    public function conferences()
    {
        return $this->publications()->where('type', 'conference')->orderBy('date', 'DESC');
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class)->orderBy('date', 'DESC');
    }

    public function courseworks()
    {
        return $this->belongsToMany(PhdCourse::class)
            ->withPivot(['completed_at'])
            ->using(ScholarCourseworkPivot::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class)
            ->whereNull('extended_leave_id')
            ->orderBy('to', 'desc');
    }

    public function advisoryMeetings()
    {
        return $this->hasMany(AdvisoryMeeting::class)->orderBy('date', 'desc');
    }
}

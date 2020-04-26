<?php

namespace App\Models;

use App\Casts\AdvisoryCommittee;
use App\Models\AcademicDetail;
use App\Models\Cosupervisor;
use App\Models\Publication;
use App\Models\ScholarProfile;
use App\Models\SupervisorProfile;
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
        'cosupervisor_id',
        'old_cosupervisors',
        'old_supervisors',
        'old_advisory_committees',
    ];

    protected $casts = [
        'advisory_committee' => AdvisoryCommittee::class,
        'education' => 'array',
        'old_cosupervisors' => 'array',
        'old_supervisors' => 'array',
        'old_advisory_committees' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(static function ($scholar) {
            $scholar->old_cosupervisors = [];
            $scholar->old_supervisors = [];
            $scholar->education = [];
            $scholar->old_advisory_committees = [];
        });

        static::created(static function ($scholar) {
            $scholar->courseworks()->attach(PhdCourse::core()->get());
        });
    }

    public function getRegisterOnAttribute()
    {
        return $this->created_at->format('d F Y');
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // public function getAdvisoryCommitteeAttribute($value)
    // {
    //     // dd($value);
    //     $value = $this->castAttribute('advisory_committee', $value);

    //     $mentors = [
    //         'supervisor' => $this->supervisor->name,
    //     ];

    //     if ($this->cosupervisor) {
    //         $mentors = array_merge($mentors, [
    //             'cosupervisor' => $this->cosupervisor->name,
    //         ]);
    //     }

    //     return $value === null ? $mentors : array_merge($value, $mentors);
    // }

    public function getEducationAttribute($value)
    {
        $value = $this->castAttribute('education', $value);

        foreach ($value as $index => $education) {
            $value[$index]['subject'] = ScholarEducationSubject::find($education['subject'])->name;
            $value[$index]['degree'] = ScholarEducationDegree::find($education['degree'])->name;
            $value[$index]['institute'] = ScholarEducationInstitute::find($education['institute'])->name;
            $value[$index]['year'] = $education['year'];
        }

        return $value;
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

    public function cosupervisor()
    {
        return $this->belongsTo(Cosupervisor::class);
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

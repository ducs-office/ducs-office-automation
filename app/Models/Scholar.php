<?php

namespace App\Models;

use App\Casts\AdvisoryCommittee;
use App\Casts\CustomType;
use App\Casts\EducationDetails;
use App\Casts\OldAdvisoryCommittee;
use App\Concerns\HasPublications;
use App\Models\AcademicDetail;
use App\Models\Cosupervisor;
use App\Models\Publication;
use App\Models\ScholarProfile;
use App\Models\SupervisorProfile;
use App\Types\AdmissionMode;
use App\Types\Gender;
use App\Types\ReservationCategory;
use App\Types\ScholarDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Scholar extends User
{
    use HasPublications;

    protected $hidden = ['password'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'term_duration',
        'phone_no',
        'address',
        'category',
        'admission_mode',
        'supervisor_profile_id',
        'gender',
        'research_area',
        'registration_date',
        'advisory_committee',
        'education_details',
        'cosupervisor_profile_id',
        'cosupervisor_profile_type',
        'old_cosupervisors',
        'old_supervisors',
        'old_advisory_committees',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'advisory_committee' => AdvisoryCommittee::class,
        'old_advisory_committees' => OldAdvisoryCommittee::class,
        'category' => CustomType::class . ':' . ReservationCategory::class,
        'admission_mode' => CustomType::class . ':' . AdmissionMode::class,
        'gender' => CustomType::class . ':' . Gender::class,
        'education_details' => EducationDetails::class,
        'old_cosupervisors' => 'array',
        'old_supervisors' => 'array',
    ];

    protected $withCount = [
        'courseworks', 'completedCourseworks',
        'journals', 'conferences',
        'presentations',
        'advisoryMeetings',
        'leaves', 'approvedLeaves',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(static function ($scholar) {
            $scholar->old_cosupervisors = [];
            $scholar->old_supervisors = [];
            $scholar->old_advisory_committees = [];

            if ($scholar->education_details === null) {
                $scholar->education_details = [];
            }
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

    public function getRegistrationValidUptoAttribute()
    {
        return optional($this->registration_date)->addYears($this->term_duration);
    }

    public function profilePicture()
    {
        return $this->morphOne(Attachment::class, 'attachable');
        return $this->belongsTo(SupervisorProfile::class);
    }

    public function supervisor()
    {
        return $this->supervisorProfile->supervisor();
    }

    public function supervisorProfile()
    {
        return $this->belongsTo(SupervisorProfile::class);
    }

    public function cosupervisorProfile()
    {
        return $this->morphTo('cosupervisor_profile');
    }

    public function getCosupervisorAttribute()
    {
        if ($this->cosupervisor_profile_type === SupervisorProfile::class) {
            $cosupervisor = $this->cosupervisorProfile->supervisor;

            return (object) [
                'name' => $cosupervisor->name,
                'email' => $cosupervisor->email,
                'designation' => $cosupervisor->profile->designation ?? 'Professor',
                'affiliation' => $cosupervisor->supervisor_type === User::class ? 'DUCS' :
                                $cosupervisor->profile->college->name ?? 'Affiliation Not Set',
            ];
        }

        return $this->cosupervisorProfile;
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class)->orderBy('date', 'DESC');
    }

    public function courseworks()
    {
        return $this->belongsToMany(PhdCourse::class)
            ->withPivot(['completed_on', 'marksheet_path', 'id'])
            ->using(ScholarCourseworkPivot::class);
    }

    public function completedCourseworks()
    {
        return $this->courseworks()->wherePivot('completed_on', '<>', null);
    }

    public function addCourse(PhdCourse $course, $attributes = [])
    {
        return $this->courseworks()->syncWithoutDetaching([$course->id => $attributes]);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class)
            ->whereNull('extended_leave_id')
            ->orderBy('to', 'desc');
    }

    public function approvedLeaves()
    {
        return $this->leaves()->where('status', LeaveStatus::APPROVED);
    }

    public function advisoryMeetings()
    {
        return $this->hasMany(AdvisoryMeeting::class)->orderBy('date', 'desc');
    }

    public function documents()
    {
        return $this->hasMany(ScholarDocument::class)->orderBy('date', 'desc');
    }

    public function progressReports()
    {
        return $this->documents->where('type', ScholarDocumentType::PROGRESS_REPORT);
    }

    public function otherDocuments()
    {
        return $this->documents->where('type', ScholarDocumentType::OTHER_DOCUMENT);
    }
}

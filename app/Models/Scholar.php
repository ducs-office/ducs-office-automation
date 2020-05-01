<?php

namespace App\Models;

use App\Casts\AdvisoryCommittee;
use App\Casts\CustomType;
use App\Casts\OldAdvisoryCommittee;
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
    protected $hidden = ['password'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_no',
        'address',
        'category',
        'admission_mode',
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
        'old_advisory_committees' => OldAdvisoryCommittee::class,
        'category' => CustomType::class . ':' . ReservationCategory::class,
        'admission_mode' => CustomType::class . ':' . AdmissionMode::class,
        'gender' => CustomType::class . ':' . Gender::class,
        'education' => 'array',
        'old_cosupervisors' => 'array',
        'old_supervisors' => 'array',
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
            ->withPivot(['completed_on', 'marksheet_path', 'id'])
            ->using(ScholarCourseworkPivot::class);
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

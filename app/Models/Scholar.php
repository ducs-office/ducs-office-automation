<?php

namespace App\Models;

use App\Casts\AdvisoryCommittee;
use App\Casts\CustomType;
use App\Casts\EducationDetails;
use App\Casts\OldAdvisoryCommittee;
use App\Concerns\HasPublications;
use App\Concerns\HasResearchCommittee;
use App\Models\Cosupervisor;
use App\Models\ExternalAuthority;
use App\Models\Pivot\ScholarCoursework;
use App\Models\Pivot\ScholarSupervisor;
use App\Models\Publication;
use App\Models\ScholarAdvisor;
use App\Models\ScholarAppeal;
use App\Types\AdmissionMode;
use App\Types\Gender;
use App\Types\LeaveStatus;
use App\Types\RequestStatus;
use App\Types\ReservationCategory;
use App\Types\ScholarAppealTypes;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Scholar extends User
{
    use Notifiable, HasPublications, HasResearchCommittee;

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
        'gender',
        'research_area',
        'registration_date',
        'enrolment_id',
        'advisory_committee',
        'education_details',
        'old_advisory_committees',
        'finalized_title',
        'recommended_title',
        'title_finalized_on',
        'title_recommended_on',
        'proposed_title',
        'examiner_status',
        'examiner_applied_on',
        'examiner_recommended_on',
        'examiner_approved_on',
    ];

    protected $dates = [
        'registration_date',
        'title_finalized_on',
        'title_recommended_on',
        'examiner_applied_on',
        'examiner_recommended_on',
        'examiner_approved_on',
    ];

    protected $casts = [
        'category' => CustomType::class . ':' . ReservationCategory::class,
        'admission_mode' => CustomType::class . ':' . AdmissionMode::class,
        'gender' => CustomType::class . ':' . Gender::class,
        'education_details' => EducationDetails::class,
        'examiner_status' => CustomType::class . ':' . RequestStatus::class,
    ];

    protected $withCount = [
        'courseworks', 'completedCourseworks',
        'journals', 'conferences',
        'presentations',
        'advisoryMeetings',
        'leaves', 'approvedLeaves',
        'appeals',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(static function ($scholar) {
            if ($scholar->education_details === null) {
                $scholar->education_details = [];
            }
        });

        static::created(static function ($scholar) {
            $scholar->courseworks()->attach(PhdCourse::core()->get());
        });
    }

    public function getRegistrationValidUptoAttribute()
    {
        return optional($this->registration_date)->addYears($this->term_duration);
    }

    public function profilePicture()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class)->orderBy('date', 'DESC');
    }

    public function courseworks()
    {
        return $this->belongsToMany(PhdCourse::class)
            ->withPivot(['completed_on', 'marksheet_path', 'id'])
            ->using(ScholarCoursework::class);
    }

    public function completedCourseworks()
    {
        return $this->courseworks()->wherePivot('completed_on', '<>', null);
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
        return $this->hasMany(ProgressReport::class)->orderBy('date', 'desc');
    }

    public function appeals()
    {
        return $this->hasMany(ScholarAppeal::class, 'scholar_id');
    }

    public function currentPhdSeminarAppeal()
    {
        return optional($this->phdSeminarAppeals())->first();
    }

    public function prePhdSeminar()
    {
        return $this->hasOne(PrePhdSeminar::class);
    }

    public function isJoiningLetterUploaded()
    {
        return $this->documents()->where('type', ScholarDocumentType::JOINING_LETTER)->exists();
    }

    public function isAcceptanceLetterUploaded()
    {
        return $this->documents()->where('type', ScholarDocumentType::ACCEPTANCE_LETTER)->exists();
    }

    public function isTableOfContentsOfThesisUploaded()
    {
        return $this->documents()->where('type', ScholarDocumentType::THESIS_TOC)->exists();
    }

    public function isPrePhdSeminarNoticeUploaded()
    {
        return $this->documents()->where('type', ScholarDocumentType::PRE_PHD_SEMINAR_NOTICE)->exists();
    }

    public function canApplyForPrePhdSeminar()
    {
        return $this->isJoiningLetterUploaded()
            && $this->areCourseworksCompleted()
            && $this->journals()->count()
            && $this->proposed_title;
    }

    public function titleApprovalAppeal()
    {
        return $this->appeals()->where('type', ScholarAppealTypes::TITLE_APPROVAL)->orderBY('created_at', 'DESC')->first();
    }

    public function isTitleApprovalDocumentListCompleted()
    {
        return $this->isJoiningLetterUploaded()
            && $this->isTableOfContentsOfThesisUploaded()
            && $this->isPrePhdSeminarNoticeUploaded();
    }

    public function areCourseworksCompleted()
    {
        return $this->courseworks()->whereNull('completed_on')->count() === 0;
    }

    // Helpers
    public function addCourse(PhdCourse $course, $attributes = [])
    {
        return $this->courseworks()->syncWithoutDetaching([$course->id => $attributes]);
    }

    // Accessors
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

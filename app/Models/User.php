<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Concerns\HasPublications;
use App\Types\Designation;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasPublications;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'category',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'category' => CustomType::class . ':' . UserCategory::class,
        'designation' => CustomType::class . ':' . Designation::class,
        'status' => CustomType::class . ':' . TeacherStatus::class,
        'is_admin' => 'boolean',
        'is_supervisor' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(static function ($user) {
            $user->roles()->sync([]);
            $user->remarks()->update(['user_id' => null]);
        });
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function setNameAttribute($name)
    {
        if (! Str::contains($name, ' ')) {
            $name .= ' .';
        }

        list($this->first_name, $this->last_name) = explode(' ', $name, 2);
    }

    public function remarks()
    {
        return $this->hasMany(Remark::class, 'user_id');
    }

    public function sentLetters()
    {
        return $this->hasMany(OutgoingLetter::class, 'sender_id');
    }

    public function createdOutgoingLetters()
    {
        return $this->hasMany(OutgoingLetter::class, 'creator_id');
    }

    public function receivedLetters()
    {
        return $this->hasMany(IncomingLetter::class, 'recipient_id');
    }

    public function createdIncomingLetters()
    {
        return $this->hasMany(IncomingLetter::class, 'creator_id');
    }

    public function canBecomeSupervisor()
    {
        return in_array($this->category, [
            UserCategory::COLLEGE_TEACHER,
            UserCategory::FACULTY_TEACHER,
        ]);
    }

    public function supervisorProfile()
    {
        return $this->hasOne(SupervisorProfile::class, 'supervisor_id');
    }

    public function isSupervisor()
    {
        return $this->supervisorProfile !== null;
    }

    public function cosupervisorProfile()
    {
        return $this->hasOne(Cosupervisor::class);
    }

    public function isCosupervisor()
    {
        return $this->cosupervisorProfile !== null;
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function teachingDetails()
    {
        return $this->hasMany(TeachingDetail::class, 'teacher_id');
    }

    public function teachingRecords()
    {
        return $this->hasMany(TeachingRecord::class, 'teacher_id')->orderBy('valid_from', 'desc');
    }
}

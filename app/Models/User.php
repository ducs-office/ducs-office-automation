<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Concerns\ActsAsCosupervisor;
use App\Concerns\ActsAsSupervisor;
use App\Concerns\Filterable;
use App\Concerns\HasPublications;
use App\Filters\User\ByCategory;
use App\Filters\User\SearchByName;
use App\Types\Designation;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable,
        HasRoles,
        Filterable,
        HasPublications,
        ActsAsSupervisor,
        ActsAsCosupervisor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'first_name', 'last_name', 'email', 'password', 'category',
        'phone', 'address', 'college_id', 'designation', 'affiliation',
        'status', 'avatar_path', 'is_supervisor', 'is_cosupervisor',
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
        'category' => CustomType::class . ':' . UserCategory::class,
        'status' => CustomType::class . ':' . TeacherStatus::class,
        'is_supervisor' => 'boolean',
        'is_cosupervisor' => 'boolean',
        'is_admin' => 'boolean',
    ];

    protected $filters = [
        ByCategory::class,
        SearchByName::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(static function ($user) {
            $user->roles()->sync([]);
            $user->remarks()->update(['user_id' => null]);
        });
    }

    // Query Scopes
    public function scopeFacultyTeachers(Builder $builder)
    {
        return $builder->where('category', UserCategory::FACULTY_TEACHER);
    }

    public function scopeCollegeTeachers(Builder $builder)
    {
        return $builder->where('category', UserCategory::COLLEGE_TEACHER);
    }

    public function scopeAllTeachers(Builder $builder)
    {
        return $builder->whereIn('category', [UserCategory::FACULTY_TEACHER, UserCategory::COLLEGE_TEACHER]);
    }

    // Eloquent Relations
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

    public function scholars()
    {
        return $this->belongsToMany(Scholar::class, 'scholar_supervisor', 'supervisor_id');
    }

    // Accessors & Mutators
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

    public function getAffiliationAttribute($affiliation)
    {
        return $affiliation ?? optional($this->college)->name ?? 'Unknown';
    }

    public function getAvatarUrl()
    {
        if ($this->avatar_path != null && Storage::exists($this->avatar_path)) {
            return route('profiles.avatar', $this);
        }

        return 'https://gravatar.com/avatar/'
            . md5(strtolower(trim($this->email)))
            . '?s=200&d=identicon';
    }

    // Helpers
    public function isCollegeTeacher()
    {
        return $this->category->equals(UserCategory::COLLEGE_TEACHER);
    }

    public function isFacultyTeacher()
    {
        return $this->category->equals(UserCategory::FACULTY_TEACHER);
    }

    public function isProfileComplete()
    {
        return $this->college_id != null
            && $this->designation != null
            && $this->status != null
            && $this->teachingDetails->count() > 0;
    }
}

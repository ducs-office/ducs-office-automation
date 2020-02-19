<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PastTeachersProfile extends Model
{
    protected static $accept_details_key_prefix = 'accept_details';

    protected $guarded = [''];

    protected $dates = ['valid_from'];

    public static function startAccepting($start, $end)
    {
        return Cache::put(static::$accept_details_key_prefix . '_start', $start)
         && Cache::put(static::$accept_details_key_prefix . '_end', $end);
    }

    public static function canSubmit($teacher)
    {
        return static::getStartDate() < now()
            && static::getEndDate() > now()
            && ! static::where('valid_from', static::getStartDate())
                ->where('teacher_id', $teacher->id)->exists();
    }

    public static function extendDeadline($extend_to)
    {
        return Cache::put(static::$accept_details_key_prefix . '_end', $extend_to);
    }

    public static function getStartDate()
    {
        return Cache::get(static::$accept_details_key_prefix . '_start');
    }

    public static function getEndDate()
    {
        return Cache::get(static::$accept_details_key_prefix . '_end');
    }

    public function past_teaching_details()
    {
        return $this->belongsToMany(CourseProgrammeRevision::class, 'past_teaching_details', 'past_teachers_profile_id', 'course_programme_revision_id');
    }

    public function college()
    {
        return $this->belongsTo('App\College');
    }

    public function getDesignation()
    {
        return config('options.teachers.designations')[$this->designation];
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TeachingRecord extends Model
{
    protected static $acceptRecordsKeyPrefix = 'teaching_records_';

    protected $guarded = [];
    protected $dates = ['valid_from'];

    public static function isAccepting()
    {
        return static::getStartDate() && static::getStartDate() < now()
            && static::getEndDate() && static::getEndDate() > now();
    }

    public static function startAccepting($start, $end)
    {
        return Cache::put(static::$acceptRecordsKeyPrefix . 'start', $start)
            && Cache::put(static::$acceptRecordsKeyPrefix . 'end', $end);
    }

    public static function canSubmit($teacher)
    {
        return static::isAccepting()
            && ! static::where('valid_from', static::getStartDate())
                ->where('teacher_id', $teacher->id)->exists();
    }

    public static function extendDeadline($extend_to)
    {
        return Cache::put(static::$acceptRecordsKeyPrefix . 'end', $extend_to);
    }

    public static function getStartDate()
    {
        return Cache::get(static::$acceptRecordsKeyPrefix . 'start');
    }

    public static function getEndDate()
    {
        return Cache::get(static::$acceptRecordsKeyPrefix . 'end');
    }

    public function scopeFilter(Builder $query, $filters)
    {
        if (! isset($filters['valid_from']) && ! isset($filters['course_id'])) {
            return $query;
        }

        if (isset($filters['valid_from'])) {
            $query->where('valid_from', '>=', $filters['valid_from']);
        }

        if (isset($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }
    }

    public function scopeFilterByCourse(Builder $query, $course_id)
    {
        if ($course_id != null) {
            $query->where('course_id', $course_id);
        }
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function programmeRevision()
    {
        return $this->belongsTo(ProgrammeRevision::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function getDesignation()
    {
        return config('options.teachers.designations')[$this->designation] ?? 'Unknown';
    }
}

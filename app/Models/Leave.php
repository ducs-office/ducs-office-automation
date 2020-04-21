<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'from', 'to', 'reason',
        'scholar_id', 'status',
        'extended_leave_id',
        'document_path',
    ];

    protected $casts = [
        'from' => 'date',
        'to' => 'date',
    ];

    public function approve()
    {
        $this->status = LeaveStatus::APPROVED;
        return $this->save();
    }

    public function recommend()
    {
        $this->status = LeaveStatus::RECOMMENDED;
        return $this->save();
    }

    public function isApproved()
    {
        return $this->status === LeaveStatus::APPROVED;
    }

    public function isRecommended()
    {
        return $this->status === LeaveStatus::RECOMMENDED;
    }

    public function extensions()
    {
        return $this->hasMany(Leave::class, 'extended_leave_id');
    }

    public function extendedLeave()
    {
        return $this->belongsTo(Leave::class, 'extended_leave_id');
    }

    public function nextExtensionFrom()
    {
        return collect([$this])
            ->concat($this->extensions)
            ->filter->isApproved()
            ->max('to')->addDay();
    }
}

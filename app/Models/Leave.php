<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\LeaveStatus;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'from', 'to', 'reason',
        'scholar_id', 'status',
        'extended_leave_id',
        'application_path',
        'response_letter_path',
    ];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
        'status' => CustomType::class . ':' . LeaveStatus::class,
    ];

    public function approve()
    {
        $this->status = LeaveStatus::APPROVED;
        return $this->save();
    }

    public function reject()
    {
        $this->status = LeaveStatus::REJECTED;
        return $this->save();
    }

    public function recommend()
    {
        $this->status = LeaveStatus::RECOMMENDED;
        return $this->save();
    }

    public function isApproved()
    {
        return $this->status->equals(LeaveStatus::APPROVED);
    }

    public function isRejected()
    {
        return $this->status->equals(LeaveStatus::REJECTED);
    }

    public function isRecommended()
    {
        return $this->status->equals(LeaveStatus::RECOMMENDED);
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
        return optional(
            collect($this->extensions)
                ->push($this)
                ->filter->isApproved()
                ->max('to')
        )->addDay();
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}

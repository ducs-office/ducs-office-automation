<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = ['from', 'to', 'reason', 'scholar_id', 'status'];

    protected $casts = [
        'from' => 'date',
        'to' => 'date',
    ];

    public function approve()
    {
        $this->status = LeaveStatus::APPROVED;
        return $this->save();
    }

    public function isApproved()
    {
        return $this->status === LeaveStatus::APPROVED;
    }
}

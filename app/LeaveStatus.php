<?php

namespace App;

class LeaveStatus
{
    const REJECTED = 'rejected';
    const APPLIED = 'applied';
    const APPROVED = 'approved';

    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public static function values()
    {
        return [self::REJECTED, self::APPLIED, self::APPROVED];
    }
}

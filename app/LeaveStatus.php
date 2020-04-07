<?php

namespace App;

class LeaveStatus
{
    const APPLIED = 'applied';
    const RECOMMENDED = 'recommended';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';

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
        return [self::APPLIED, self::RECOMMENDED, self::APPROVED, self::REJECTED];
    }
}

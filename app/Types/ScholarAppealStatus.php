<?php

namespace App\Types;

use App\Types\BaseEnumType;

class ScholarAppealStatus extends BaseEnumType
{
    const APPLIED = 'applied';
    const RECOMMENDED = 'recommended';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const COMPLETED = 'completed';

    public function getContextIcon()
    {
        return [
            self::APPROVED => 'check-circle',
            self::REJECTED => 'x-circle',
            self::RECOMMENDED => 'shield',
            self::APPLIED => 'alert-circle',
        ][$this->value];
    }

    public function getContextCSS()
    {
        return [
            self::APPROVED => 'text-green-500',
            self::REJECTED => 'text-red-600',
            self::RECOMMENDED => 'text-blue-600',
            self::APPLIED => 'text-gray-700',
        ][$this->value];
    }
}

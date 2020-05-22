<?php

namespace App\Types;

use App\Types\BaseEnumType;

class ScholarAppealStatus extends BaseEnumType
{
    const APPLIED = 'applied';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const COMPLETED = 'completed';
    const RECOMMENDED = 'recommend';

    public function getContextIcon()
    {
        return [
            self::APPROVED => 'check-circle',
            self::REJECTED => 'x-circle',
            self::APPLIED => 'alert-circle',
            self::COMPLETED => 'check-circle',
            self::RECOMMENDED => 'check-circle',
        ][$this->value];
    }

    public function getContextCSS()
    {
        return [
            self::APPROVED => 'text-green-500 bg-green-300 border-green-500',
            self::REJECTED => 'text-red-600',
            self::APPLIED => 'text-gray-600 bg-gray-200',
            self::COMPLETED => 'text-magenta-800 bg-magenta-200 border-magenta-800',
        ][$this->value];
    }
}

<?php

namespace App\Types;

use App\Types\BaseEnumType;

class RequestStatus extends BaseEnumType
{
    const APPLIED = 'applied';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const RECOMMENDED = 'recommended';
    const COMPLETED = 'completed';

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

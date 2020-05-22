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
            self::APPROVED => 'bg-green-300 text-green-500',
            self::REJECTED => 'bg-red-300 text-red-600',
            self::RECOMMENDED => 'bg-blue-300 text-blue-600',
            self::APPLIED => 'bg-gray-300 text-gray-700',
        ][$this->value];
    }
}

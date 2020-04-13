<?php

namespace App\Types;

use App\Types\BaseEnumType;

class Priority extends BaseEnumType
{
    const LOW = 'LOW';
    const MEDIUM = 'MEDIUM';
    const HIGH = 'HIGH';

    public function getContextCSS()
    {
        return [
            self::LOW => 'text-yellow-800',
            self::MEDIUM => 'text-blue-600',
            self::HIGH => 'text-red-600',
        ][$this->value];
    }

    public function getDegree()
    {
        return array_search($this->value, [
            null,
            self::LOW,
            self::MEDIUM,
            self::HIGH,
        ]);
    }
}

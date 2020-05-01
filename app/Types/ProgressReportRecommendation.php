<?php

namespace App\Types;

class ProgressReportRecommendation extends BaseEnumType
{
    const CONTINUE = 'Continue';
    const CANCELLATION = 'Cancellation';
    const WARNING = 'Warning';

    public function getContextCSS()
    {
        return [
            self::CANCELLATION => 'bg-red-600',
            self::WARNING => 'bg-yellow-400',
            self::CONTINUE => 'bg-green-400',
        ][$this->value];
    }
}

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
            self::CANCELLATION => 'bg-red-300 text-red-900',
            self::WARNING => 'bg-yellow-300 text-yellow-900',
            self::CONTINUE => 'bg-green-300 text-green-900',
        ][$this->value];
    }
}

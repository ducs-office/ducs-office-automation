<?php

namespace App\Types;

use Illuminate\Database\Eloquent\Model;

class ProgressReportRecommendation extends Model
{
    const CONTINUE = 'Continue';
    const CANCELLATION = 'Cancellation';
    const WARNING = 'Warning';

    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function _toString()
    {
        return $this->value;
    }

    public static function values()
    {
        return [self::CONTINUE, self::CANCELLATION, self::WARNING];
    }
}

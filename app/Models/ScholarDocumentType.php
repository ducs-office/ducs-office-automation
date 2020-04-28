<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarDocumentType extends Model
{
    const PROGRESS_REPORT = 'progress report';
    const OTHER_DOCUMENT = 'other document';

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
        return [self::PROGRESS_REPORT, self::OTHER_DOCUMENT];
    }
}

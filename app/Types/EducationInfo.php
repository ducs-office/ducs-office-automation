<?php

namespace App\Types;

class EducationInfo
{
    public $degree;
    public $subject;
    public $institute;
    public $year;

    public function __construct($attributes)
    {
        extract($attributes);
        $this->degree = $degree;
        $this->subject = $subject;
        $this->institute = $institute;
        $this->year = $year;
    }

    public function toArray()
    {
        return [
            'degree' => $this->degree,
            'subject' => $this->subject,
            'institute' => $this->institute,
            'year' => $this->year,
        ];
    }
}

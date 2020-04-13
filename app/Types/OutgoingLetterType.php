<?php

namespace App\Types;

use App\Types\BaseEnumType;

class OutgoingLetterType extends BaseEnumType
{
    const GENERAL = 'General';
    const NOTESHEET = 'Notesheet';
    const BILL = 'Bill';

    public function contextCSS()
    {
        return [
            self::GENERAL => 'bg-magenta-700',
            self::NOTESHEET => 'bg-teal-600',
            self::BILL => 'bg-blue-600',
        ][$this->value];
    }

    public function serialPrefix()
    {
        return [
            self::GENERAL => '',
            self::NOTESHEET => 'NTS/',
            self::BILL => 'TR/',
        ][$this->value];
    }
}

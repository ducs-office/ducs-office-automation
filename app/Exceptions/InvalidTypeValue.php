<?php

namespace App\Exceptions;

class InvalidTypeValue extends \Exception
{
    public function __construct($value, $type)
    {
        parent::__construct("'{$value}' is not a valid type value in '{$type}'");
    }
}

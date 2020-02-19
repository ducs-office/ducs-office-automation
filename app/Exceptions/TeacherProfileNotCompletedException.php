<?php

namespace App\Exceptions;

use App\TeacherProfile;
use Exception;

class TeacherProfileNotCompletedException extends Exception
{
    public function render()
    {
        flash($this->getMessage())->error();

        return redirect()->back();
    }
}

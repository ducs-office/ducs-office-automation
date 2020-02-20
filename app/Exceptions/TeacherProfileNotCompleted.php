<?php

namespace App\Exceptions;

use Exception;

class TeacherProfileNotCompleted extends Exception
{
    public function render()
    {
        flash($this->getMessage())->error();

        return redirect()->back();
    }
}

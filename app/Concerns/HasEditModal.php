<?php

namespace App\Concerns;

use App\Models\Course;

trait HasEditModal
{
    public function close()
    {
        $this->showModal = false;
    }

    public function onShow()
    {
    }
}

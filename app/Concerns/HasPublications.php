<?php

namespace App\Concerns;

use App\Models\Publication;

trait HasPublications
{
    public function publications()
    {
        return $this->morphMany(Publication::class, 'main_author');
    }

    public function journals()
    {
        return $this->publications()->journal();
    }

    public function conferences()
    {
        return $this->publications()->conference();
    }
}

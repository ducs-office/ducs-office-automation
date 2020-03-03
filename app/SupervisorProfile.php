<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupervisorProfile extends Model
{
    public function supervisor()
    {
        return $this->morphTo('supervisor');
    }
}

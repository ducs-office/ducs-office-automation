<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarProfilePolicy
{
    use HandlesAuthorization;

    public function manageAdvisoryCommittee($user, Scholar $scholar)
    {
        return get_class($user) === User::class &&
            $user->isSupervisor() &&
            (int) $user->id === (int) $scholar->currentSupervisor->id;
    }
}

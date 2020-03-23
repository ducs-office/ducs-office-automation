<?php

namespace App\Policies;

use App\Scholar;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarProfilePolicy
{
    use HandlesAuthorization;

    public function addCoursework($user, Scholar $scholar)
    {
        return method_exists($user, 'isSupervisor') &&
            $user->isSupervisor() &&
            $user->supervisorProfile->scholars->contains($scholar);
    }

    public function markCourseworkCompleted($user, Scholar $scholar)
    {
        return method_exists($user, 'isSupervisor') &&
            $user->isSupervisor() &&
            $user->supervisorProfile->scholars->contains($scholar);
    }

    public function addAdvisoryMeeting($user, Scholar $scholar)
    {
        return method_exists($user, 'isSupervisor') &&
            $user->isSupervisor() &&
            $user->supervisorProfile->scholars->contains($scholar);
    }
}

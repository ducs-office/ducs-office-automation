<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\User;
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
        return $user->can('phd course work:mark completed');
    }

    public function addAdvisoryMeeting($user, Scholar $scholar)
    {
        return method_exists($user, 'isSupervisor') &&
            $user->isSupervisor() &&
            $user->supervisorProfile->scholars->contains($scholar);
    }

    public function addProgressReports($user, Scholar $scholar)
    {
        return ($user instanceof User && $user->can('scholar progress reports:add'));
    }

    public function addOtherDocuments($user, Scholar $scholar)
    {
        return ($user instanceof User && $user->can('scholar other documents:add'));
    }
}

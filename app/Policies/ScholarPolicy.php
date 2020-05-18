<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\User;
use App\Types\RequestStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        if (method_exists($user, 'isSupervisor') && $user->isSupervisor()) {
            return true;
        }

        return $user->can('scholars:view');
    }

    public function view($user)
    {
        if (method_exists($user, 'isSupervisor') && $user->isSupervisor()) {
            return true;
        }
        return ($user->can('scholars:view'));
    }

    public function create($user)
    {
        return $user->can('scholars:create');
    }

    public function update($user)
    {
        return $user->can('scholars:edit');
    }

    public function delete($user)
    {
        return $user->can('scholars:delete');
    }

    public function applyForExaminer($user, Scholar $scholar)
    {
        return $user instanceof User
            && method_exists($user, 'isSupervisor')
            && ($user->isSupervisor())
            && ($user->scholars->contains($scholar->id))
            && $scholar->examiner_status == null;
    }

    public function recommendExaminer($user, Scholar $scholar)
    {
        return $user instanceof User
            && $user->can('scholar examiner:recommend')
            && $scholar->examiner_status == RequestStatus::APPLIED;
    }

    public function approveExaminer($user, Scholar $scholar)
    {
        return $user instanceof User
            && $user->can('scholar examiner:approve')
            && $scholar->examiner_status == RequestStatus::RECOMMENDED;
    }
}

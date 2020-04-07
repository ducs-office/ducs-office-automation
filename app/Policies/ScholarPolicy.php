<?php

namespace App\Policies;

use App\Scholar;
use App\User;
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
        return $user->can('scholars:view');
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
}

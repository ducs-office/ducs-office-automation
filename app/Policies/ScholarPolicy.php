<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('scholars:view');
    }

    public function view(User $user)
    {
        return $user->can('scholars:view');
    }

    public function create(User $user)
    {
        return $user->can('scholars:create');
    }

    public function update(User $user)
    {
        return $user->can('scholars:edit');
    }

    public function delete(User $user)
    {
        return $user->can('scholars:delete');
    }
}

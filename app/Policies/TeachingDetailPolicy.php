<?php

namespace App\Policies;

use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeachingDetailPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->category->equals(UserCategory::COLLEGE_TEACHER);
    }
}

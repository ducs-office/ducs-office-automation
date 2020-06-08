<?php

namespace App\Policies;

use App\Models\TeachingDetail;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeachingDetailPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->category->equals(UserCategory::COLLEGE_TEACHER);
    }

    public function create(User $user)
    {
        return $user->category->equals(UserCategory::COLLEGE_TEACHER)
            && $user->teachingDetails->count() <= 3;
    }

    public function delete(User $user, TeachingDetail $teachingDetail)
    {
        return $user->category->equals(UserCategory::COLLEGE_TEACHER)
            && (int) $user->id == (int) $teachingDetail->teacher_id;
    }
}

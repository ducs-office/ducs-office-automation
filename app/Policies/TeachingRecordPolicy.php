<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\TeachingDetail;
use App\Models\TeachingRecord;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeachingRecordPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('teaching records:view');
    }

    public function start(User $user)
    {
        return $user->can('teaching records:start');
    }

    public function extend(User $user)
    {
        return $user->can('teaching records:extend');
    }

    public function create(User $user)
    {
        return $user->can('create', TeachingDetail::class)
            && TeachingRecord::canSubmit($user);
    }
}

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

    public function create(User $user)
    {
        return $user->can('create', TeachingDetail::class)
            && TeachingRecord::canSubmit($user);
    }
}

<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\ScholarExaminer;
use App\Models\User;
use App\Types\RequestStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarExaminerPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return get_class($user) === Scholar::class
            || $user->isSupervisor()
            || $user->can('scholar examiner:recommend')
            || $user->can('scholar examiner:approve');
    }

    public function create($user, Scholar $scholar)
    {
        return get_class($user) === User::class
            && ($user->isSupervisor())
            && (($user->scholars->contains($scholar->id)))
            && $scholar->examiner === null
            && $scholar->titleApproval !== null
            && $scholar->titleApproval->status == RequestStatus::APPROVED;
    }

    public function recommend($user, ScholarExaminer $examiner, Scholar $scholar)
    {
        return $examiner->scholar->id === $scholar->id
            && get_class($user) === User::class
            && $user->can('scholar examiner:recommend')
            && $examiner->status == RequestStatus::APPLIED;
    }

    public function approve($user, ScholarExaminer $examiner, Scholar $scholar)
    {
        return $examiner->scholar->id === $scholar->id
            && get_class($user) === User::class
            && $user->can('scholar examiner:approve')
            && $examiner->status == RequestStatus::RECOMMENDED;
    }
}

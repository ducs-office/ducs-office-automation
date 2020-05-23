<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\TitleApproval;
use App\Models\User;
use App\Types\RequestStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class TitleApprovalPolicy
{
    use HandlesAuthorization;

    public function request($user)
    {
        return $user instanceof Scholar
            && $user->prePhdSeminar
            && $user->prePhdSeminar->isCompleted()
            && $user->titleApproval === null;
    }

    public function apply($user, Scholar $scholar)
    {
        return $user instanceof Scholar
            && $user->id === $scholar->id
            && $scholar->titleApproval === null
            && $scholar->canApplyForTitleApproval();
    }

    public function view($user, Scholar $scholar, TitleApproval $appeal)
    {
        if ($scholar->titleApproval && $scholar->is($appeal->scholar)) {
            if (get_class($user) === Scholar::class) {
                return (int) $user->id === (int) $scholar->id;
            } elseif ($user->isSupervisor()) {
                return $user->scholars->contains($scholar);
            }

            return $user->can('title approval:approve');
        }

        return false;
    }

    public function recommend($user, Scholar $scholar, TitleApproval $appeal)
    {
        return $scholar->is($appeal->scholar)
            && get_class($user) === User::class
            && $user->isSupervisor()
            && $user->scholars->contains($scholar)
            && $appeal->status == RequestStatus::APPLIED;
    }

    public function approve($user, Scholar $scholar, TitleApproval $appeal)
    {
        return $scholar->is($appeal->scholar)
            && $user instanceof User
            && $appeal->status == RequestStatus::RECOMMENDED
            && $user->can('title approval:approve');
    }
}

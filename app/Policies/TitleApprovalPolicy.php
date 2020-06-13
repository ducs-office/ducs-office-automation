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

    public function viewAny($user)
    {
        if (get_class($user) === Scholar::class
        || $user->isSupervisor()
        || $user->can('title approval:approve')) {
            return true;
        }
        return false;
    }

    public function request($user)
    {
        return get_class($user) === Scholar::class
            && $user->prePhdSeminar
            && $user->prePhdSeminar->isCompleted()
            && $user->titleApproval === null;
    }

    public function create($user, Scholar $scholar)
    {
        return get_class($user) === Scholar::class
            && $user->id === $scholar->id
            && $scholar->titleApproval === null
            && $scholar->canApplyForTitleApproval();
    }

    public function view($user, TitleApproval $titleApproval, Scholar $scholar)
    {
        if ($scholar->titleApproval && $scholar->is($titleApproval->scholar)) {
            if (get_class($user) === Scholar::class) {
                return (int) $user->id === (int) $scholar->id;
            } elseif ($user->isSupervisor()) {
                return $user->scholars->contains($scholar);
            }

            return $user->can('title approval:approve');
        }

        return false;
    }

    public function recommend($user, TitleApproval $titleApproval, Scholar $scholar)
    {
        return $scholar->is($titleApproval->scholar)
            && get_class($user) === User::class
            && $user->isSupervisor()
            && $user->scholars->contains($scholar)
            && $titleApproval->status == RequestStatus::APPLIED;
    }

    public function approve($user, TitleApproval $titleApproval, Scholar $scholar)
    {
        return $scholar->is($titleApproval->scholar)
            && get_class($user) === User::class
            && $titleApproval->status == RequestStatus::RECOMMENDED
            && $user->can('title approval:approve');
    }
}

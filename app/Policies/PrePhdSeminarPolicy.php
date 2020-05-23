<?php

namespace App\Policies;

use App\Models\PrePhdSeminar;
use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Models\User;
use App\Types\RequestStatus;
use App\Types\ScholarAppealStatus;
use Illuminate\Auth\Access\HandlesAuthorization;
use phpDocumentor\Reflection\Types\Null_;

class PrePhdSeminarPolicy
{
    use HandlesAuthorization;

    public function request($user)
    {
        return $user instanceof Scholar
            && $user->prePhdSeminar === null;
    }

    public function create($user, Scholar $scholar)
    {
        return $user instanceof Scholar
            && $user->id === $scholar->id
            && $scholar->prePhdSeminar === null
            && $scholar->canApplyForPrePhdSeminar();
    }

    public function view($user, PrePhdSeminar $prePhdSeminar, Scholar $scholar)
    {
        if ($scholar->prePhdSeminar && $scholar->is($prePhdSeminar->scholar)) {
            if (get_class($user) === Scholar::class) {
                return (int) $user->id === (int) $scholar->id;
            } elseif ($user->isSupervisor()) {
                return $user->scholars->contains($scholar);
            }

            return $user->can('phd seminar:finalize')
                || $user->can('phd seminar:add schedule') ;
        }

        return false;
    }

    public function forward($user, PrePhdSeminar $prePhdSeminar, Scholar $scholar)
    {
        return $scholar->is($prePhdSeminar->scholar)
            && get_class($user) === User::class
            && $user->isSupervisor()
            && $user->scholars->contains($scholar)
            && $prePhdSeminar->status == RequestStatus::APPLIED;
    }

    public function addSchedule($user, PrePhdSeminar $prePhdSeminar, Scholar $scholar)
    {
        return $scholar->is($prePhdSeminar->scholar)
            && $user instanceof User
            && $prePhdSeminar->status == RequestStatus::RECOMMENDED
            && $prePhdSeminar->scheduled_on === null
            && $user->can('phd seminar:add schedule');
    }

    public function finalize($user, PrePhdSeminar $prePhdSeminar, Scholar $scholar)
    {
        return $scholar->is($prePhdSeminar->scholar)
            && $user instanceof User
            && $prePhdSeminar->status == RequestStatus::RECOMMENDED
            && $prePhdSeminar->scheduled_on
            && $user->can('phd seminar:finalize');
    }
}

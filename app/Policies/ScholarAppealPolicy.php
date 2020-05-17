<?php

namespace App\Policies;

use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Models\User;
use App\Types\ScholarAppealStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarAppealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view pre-phd form.
     *
     * @param  $user
     * @param Scholar $scholar
     *
     * @return mixed
     */
    public function viewPhdSeminarForm($user, Scholar $scholar)
    {
        return (
                (
                    get_class($user) === Scholar::class
                && $user->id === $scholar->id
                ) || (
                    get_class($user) === User::class
                && method_exists($user, 'isSupervisor')
                && $user->isSupervisor()
                && $user->supervisorProfile->scholars->contains($scholar->id)
                ) || (
                    get_class($user) === User::class
                && $user->can('scholar appeals:mark complete')
                )
        );
    }

    /**
     * Determine whether the user can request for a pre-phd seminar
     *
     * @param  $user
     *
     * @return mixed
     */
    public function requestPhDSeminar($user)
    {
        return get_class($user) === Scholar::class
            && (
                $user->currentPhdSeminarAppeal() === null
                    || $user->currentPhdSeminarAppeal()->isRejected()
            );
    }

    /**
     * Determine whether the user can apply for a pre-phd seminar
     *
     * @param  $user
     *
     * @return mixed
     */
    public function createPhDSeminar($user)
    {
        return get_class($user) === Scholar::class
            && $user->isDocumentListComplete()
            && $user->publications->count()
            && (
                $user->currentPhdSeminarAppeal() === null
                || $user->currentPhdSeminarAppeal()->isRejected()
            );
    }

    /**
     * Determine whether the user can respond to an appeal
     *
     * @param  $user
     * @param ScholarAppeal $appeal
     *
     * @return mixed
     */
    public function respond($user, ScholarAppeal $appeal)
    {
        return get_class($user) === User::class
            && method_exists($user, 'isSupervisor')
            && $user->isSupervisor()
            && $user->supervisorProfile->scholars->contains($appeal->scholar_id)
            && $appeal->status == ScholarAppealStatus::APPLIED;
    }

    /**
     * Determine whether the user can mark complate an appeal
     *
     * @param  $user
     * @param ScholarAppeal $appeal
     *
     * @return mixed
     */
    public function markComplete($user, ScholarAppeal $appeal)
    {
        return get_class($user) === User::class
            && $user->can('scholar appeals:mark complete')
            && $appeal->status == ScholarAppealStatus::APPROVED;
    }

    public function requestTitleApproval($user)
    {
        return $user instanceof Scholar
            && optional($user->currentPhdSeminarAppeal())->status == ScholarAppealStatus::COMPLETED
            && $user->titleApprovalAppeal() == null;
    }

    public function applyTitleApproval($user)
    {
        return $user instanceof Scholar
            && $user->isTitleApprovalDocumentListCompleted()
            && optional($user->currentPhdSeminarAppeal())->status == ScholarAppealStatus::COMPLETED
            && $user->titleApprovalAppeal() == null;
    }

    public function viewTitleApprovalForm($user, Scholar $scholar)
    {
        return
            $scholar->titleApprovalAppeal() != null && (
                (
                $user instanceof Scholar
                && $user->id === $scholar->id
            ) || (
                $user instanceof User
                && method_exists($user, 'isSupervisor')
                && $user->isSupervisor()
                && $user->supervisorProfile->scholars->contains($scholar->id)
            ) || (
                $user instanceof User
                && $user->can('scholar appeals:mark complete')
            )
            );
    }
}

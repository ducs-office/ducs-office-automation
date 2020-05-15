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
        return ((
            get_class($user) === Scholar::class
                && ($user->isDocumentListComplete() || $user->phdSeminarAppeal()->first())
        )
            || (
                get_class($user) === User::class
                && method_exists($user, 'isSupervisor')
                && $user->isSupervisor()
                && $user->supervisorProfile->scholars->contains($scholar->id)
                && $scholar->phdSeminarAppeal()->first()
            )
            || (
                get_class($user) === User::class
                && $user->can('scholar appeals:respond')
                && $scholar->phdSeminarAppeal()->first()
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
            && $user->phdSeminarAppeal()->isEmpty();
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
            && $user->phdSeminarAppeal()->isEmpty();
    }

    /**
     * Determine whether the user can recomend an appeal
     *
     * @param  $user
     * @param ScholarAppeal $appeal
     *
     * @return mixed
     */
    public function recommend($user, ScholarAppeal $appeal)
    {
        return get_class($user) === User::class
            && method_exists($user, 'isSupervisor')
            && $user->isSupervisor()
            && $user->supervisorProfile->scholars->contains($appeal->scholar_id)
            && $appeal->status == ScholarAppealStatus::APPLIED;
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
            && $user->can('scholar appeals:respond')
            && in_array($appeal->status, [ScholarAppealStatus::APPLIED, ScholarAppealStatus::RECOMMENDED]);
    }
}

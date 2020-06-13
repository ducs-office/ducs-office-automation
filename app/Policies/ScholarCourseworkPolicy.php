<?php

namespace App\Policies;

use App\Models\Pivot\ScholarCoursework;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarCourseworkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  $user
     *
     * @return mixed
     */
    public function viewAny($user)
    {
        if (get_class($user) === Scholar::class) {
            return true;
        }

        if (get_class($user) === User::class && (int) $user->isSupervisor()) {
            return true;
        }

        if (get_class($user) === User::class &&
            $user->can('phd course work:view marksheet')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view a scholar's courseworks's marksheet.
     *
     * @param  $user
     * @param  \App\ScholarCoursework  $course
     * @param  \App\Models\Scholar  $scholar
     *
     * @return mixed
     */
    public function view($user, ScholarCoursework $course, Scholar $scholar)
    {
        if (get_class($user) === Scholar::class &&
            $user->id === (int) $course->scholar_id) {
            return true;
        }

        if (get_class($user) === User::class &&
            (int) $scholar->currentSupervisor->id === $user->id) {
            return true;
        }

        if (get_class($user) === User::class &&
            $user->can('phd course work:view marksheet')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  $user
     * @param  \App\Models\Scholar  $scholar
     *
     * @return mixed
     */
    public function create($user, Scholar $scholar)
    {
        return get_class($user) === User::class &&
            (int) $scholar->currentSupervisor->id === $user->id;
    }

    /**
     * Determine whether the user can mark a coursework complete.
     *
     * @param  $user
     * @param  \App\Models\Scholar  $user
     *
     * @return mixed
     */
    public function markCompleted($user)
    {
        return get_class($user) === User::class &&
            $user->can('phd course work:mark completed');
    }
}

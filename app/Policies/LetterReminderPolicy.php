<?php

namespace App\Policies;

use App\Models\LetterReminder;
use App\Models\OutgoingLetter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LetterReminderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any letter reminders.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('letter reminders:view');
    }

    /**
     * Determine whether the user can view the letter reminder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LetterReminder  $letterReminder
     *
     * @return mixed
     */
    public function view(User $user, LetterReminder $letterReminder)
    {
        return $user->can('letter reminders:view');
    }

    /**
     * Determine whether the user can create letter reminders.
     *
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function create(User $user, OutgoingLetter $letter)
    {
        return $user->can('letter reminders:create')
            && (int) $letter->creator_id === (int) $user->id;
    }

    /**
     * Determine whether the user can update the letter reminder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LetterReminder  $letterReminder
     *
     * @return mixed
     */
    public function update(User $user, LetterReminder $letterReminder)
    {
        return $user->can('letter reminders:edit')
            && (int) $letterReminder->letter->creator_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the letter reminder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LetterReminder  $letterReminder
     *
     * @return mixed
     */
    public function delete(User $user, LetterReminder $letterReminder)
    {
        return $user->can('letter reminders:delete')
            && (int) $letterReminder->letter->creator_id === (int) $user->id;
    }
}

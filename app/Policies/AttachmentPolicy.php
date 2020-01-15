<?php

namespace App\Policies;

use App\Attachment;
use App\Course;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the attachment.
     *
     * @param  \App\User  $user
     * @param  \App\Attachment  $attachment
     * @return mixed
     */
    public function view(User $user, Attachment $attachment)
    {
        return $user->can('view', $attachment->attachable);
    }

    /**
     * Determine whether the user can delete the attachment.
     *
     * @param  \App\User  $user
     * @param  \App\Attachment  $attachment
     * @return mixed
     */
    public function delete(User $user, Attachment $attachment)
    {
        return $user->can('update', $attachment->attachable)
            && $attachment->attachable->attachments()->count() > 1;
    }
}

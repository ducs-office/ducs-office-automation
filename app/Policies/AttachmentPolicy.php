<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the attachment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attachment  $attachment
     *
     * @return mixed
     */
    public function view(User $user, Attachment $attachment)
    {
        return $user->can('view', $attachment->attachable);
    }

    /**
     * Determine whether the user can delete the attachment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attachment  $attachment
     *
     * @return mixed
     */
    public function delete(User $user, Attachment $attachment)
    {
        return $user->can('update', $attachment->attachable)
            && $attachment->attachable->attachments()->count() > 1;
    }
}

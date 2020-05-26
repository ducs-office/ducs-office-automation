<?php

namespace App\Policies;

use App\Models\CoAuthor;
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoAuthorPolicy
{
    use HandlesAuthorization;

    public function view($user, CoAuthor $coAuthor)
    {
        if ($coAuthor->publication->author_type === Scholar::class) {
            if (get_class($user) === Scholar::class) {
                return $user->id === (int) $coAuthor->publication->author_id;
            } else {
                return $user->isSupervisor() && $user->scholars->contains($coAuthor->publication->author_id);
            }
        }
        return false;
    }

    public function delete($user, CoAuthor $coAuthor, Publication $publication)
    {
        return get_class($user) === $publication->author_type
            && $user->id === (int) $publication->author_id
            && $publication->coAuthors->contains($coAuthor);
    }

    public function update($user, CoAuthor $coAuthor, Publication $publication)
    {
        return get_class($user) === $publication->author_type
            && $user->id === (int) $publication->author_id
            && $publication->coAuthors->contains($coAuthor);
    }

    public function create($user, Publication $publication)
    {
        return get_class($user) === $publication->author_type
            && (int) $publication->author_id === $user->id;
    }
}

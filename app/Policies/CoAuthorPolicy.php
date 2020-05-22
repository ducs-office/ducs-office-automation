<?php

namespace App\Policies;

use App\Models\CoAuthor;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoAuthorPolicy
{
    use HandlesAuthorization;

    public function view($user, CoAuthor $coAuthor)
    {
        if ($coAuthor->publication->author_type === 'App\Models\Scholar') {
            return $user instanceof Scholar && $user->id === (int) $coAuthor->publication->author_id;
        }
    }

    public function delete($user, CoAuthor $coAuthor)
    {
        if ($coAuthor->publication->author_type === 'App\Models\Scholar') {
            return $user instanceof Scholar && $user->id === (int) $coAuthor->publication->author_id;
        }
    }
}

<?php

namespace App\Policies;

use App\Models\Presentation;
use App\Models\Scholar;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PresentationPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return true;
    }

    public function view($user, Presentation $presentation)
    {
        return (get_class($user) === Scholar::class
            && (int) $presentation->publication->author_id === $user->id)
            || (
                method_exists($user, 'isSupervisor') &&
                $user->isSupervisor()
            );
    }

    public function create($user)
    {
        return get_class($user) === Scholar::class;
    }

    public function update(Scholar $scholar, Presentation $presentation)
    {
        return (int) $presentation->scholar_id === (int) $scholar->id;
    }

    public function delete(Scholar $scholar, Presentation $presentation)
    {
        return (int) $presentation->scholar_id === (int) $scholar->id;
    }
}

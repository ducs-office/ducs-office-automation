<?php

namespace App\Policies;

use App\Models\Presentation;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PresentationPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return (get_class($user) === Scholar::class ||
            (get_class($user) === User::class && $user->isSupervisor()));
    }

    public function view($user, Presentation $presentation)
    {
        if (get_class($user) === Scholar::class && (int) $presentation->scholar_id === (int) $user->id) {
            return true;
        }

        if (get_class($user) === User::class && $user->isSupervisor() && $user->scholars->contains($presentation->scholar_id)) {
            return true;
        }

        return false;
    }

    public function create($user)
    {
        return get_class($user) === Scholar::class;
    }

    public function update($user, Presentation $presentation)
    {
        return get_class($user) === Scholar::class
            && (int) $presentation->scholar_id === (int) $user->id;
    }

    public function delete($user, Presentation $presentation)
    {
        return get_class($user) === Scholar::class
            && (int) $presentation->scholar_id === (int) $user->id;
    }
}

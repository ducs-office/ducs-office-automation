<?php

namespace App\Policies;

use App\Presentation;
use App\Scholar;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PresentationPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return (Auth::guard('scholars')->check() || (
            method_exists($user, 'isSupervisor') &&
            $user->isSupervisor()
        ));
    }

    public function create(Scholar $scholar)
    {
        return true;
    }

    public function update(Scholar $scholar, Presentation $presentation)
    {
        return $presentation->scholar_id === $scholar->id;
    }

    public function delete(Scholar $scholar, Presentation $presentation)
    {
        return $presentation->scholar_id === $scholar->id;
    }
}

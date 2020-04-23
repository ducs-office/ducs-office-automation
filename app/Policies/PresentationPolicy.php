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
        return (int) $presentation->scholar_id === (int) $scholar->id;
    }

    public function delete(Scholar $scholar, Presentation $presentation)
    {
        return (int) $presentation->scholar_id === (int) $scholar->id;
    }
}

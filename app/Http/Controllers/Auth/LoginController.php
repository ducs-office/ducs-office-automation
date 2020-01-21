<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        $guards = config('auth.guards');
        return $guards[Request::get('type', 'web')]['home'];
    }

    public function guard()
    {
        return Auth::guard(Request::get('type', 'web'));
    }

    protected function validateLogin(HttpRequest $request)
    {
        $guards = implode(',', array_keys(config('auth.guards')));

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'type' => 'required|in:'.$guards,
        ]);
    }
}

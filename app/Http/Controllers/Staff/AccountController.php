<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Rules\MatchesCurrentPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function change_password(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', new MatchesCurrentPassword()],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        flash('password changed!')->success();

        return back();
    }
}

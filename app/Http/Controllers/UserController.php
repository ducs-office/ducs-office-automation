<?php

namespace App\Http\Controllers;

use App\Mail\UserRegisteredMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $users = User::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:190'],
            'email' => ['required', 'string', 'min:3', 'max:190', 'email', 'unique:users'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        $password = strtoupper(Str::random(8));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password)
        ]);

        $user->assignRole($request->role_id);

        Mail::to($user)->send(new UserRegisteredMail($user->email, $user->password));

        flash('User created successfully!')->success();

        return redirect()->back();
    }
}

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
        $users = User::with('roles')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:190'],
            'email' => ['required', 'string', 'min:3', 'max:190', 'email', 'unique:users'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'integer', 'exists:roles,id']
        ]);

        $plain_password = strtoupper(Str::random(8));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($plain_password)
        ]);

        $user->syncRoles($request->roles);

        Mail::to($user)->send(new UserRegisteredMail($user, $plain_password));

        flash('User created successfully!')->success();

        return redirect()->back();
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'min:3', 'max:190'],
            'email' => ['sometimes', 'required', 'string', 'min:3', 'max:190', 'email', 'unique:users'],
            'roles' => ['sometimes', 'required', 'array', 'min:1'],
            'roles.*' => ['sometimes', 'required', 'integer', 'exists:roles,id']
        ]);

        $user->update($request->only(['name', 'email']));

        if($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        flash('User updated successfully!')->success();

        return redirect()->back();
    }

    public function destroy(User $user) 
    {
        $user->delete();
        
        flash('User deleted successfully!')->success();

        return redirect()->back();
    }
}

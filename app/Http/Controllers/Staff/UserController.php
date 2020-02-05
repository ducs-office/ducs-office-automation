<?php

namespace App\Http\Controllers\Staff;

use App\Mail\UserRegisteredMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        return view('staff.users.index', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all(),
            'categories' => config('options.users.categories'),
        ]);
    }

    public function store(Request $request)
    {
        $categories = implode(",", array_keys(config('options.users.categories')));

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:190'],
            'email' => ['required', 'string', 'min:3', 'max:190', 'email', 'unique:users'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'integer', 'exists:roles,id'],
            'category' => ['required', 'in:'.$categories]
        ]);

        $plain_password = strtoupper(Str::random(8));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'category' => $request->category,
            'password' => bcrypt($plain_password),
        ]);

        $user->syncRoles($request->roles);

        Mail::to($user)->send(new UserRegisteredMail($user, $plain_password));

        flash('User created successfully!')->success();

        return redirect()->back();
    }

    public function update(Request $request, User $user)
    {
        $categories = implode(",", array_keys(config('options.users.categories')));

        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'min:3', 'max:190'],
            'email' => [
                'sometimes', 'required', 'string', 'min:3', 'max:190', 'email',
                Rule::unique('users')->ignore($user)
            ],
            'roles' => ['sometimes', 'required', 'array', 'min:1'],
            'roles.*' => ['sometimes', 'required', 'integer', 'exists:roles,id'],
            'category' => ['sometimes', 'in:'.$categories]
        ]);

        $user->update($request->only(['name', 'email','category']));

        if ($request->has('roles')) {
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

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
use App\Http\Requests\Staff\StoreUserRequest;
use Illuminate\Support\Facades\DB;

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

    public function store(StoreUserRequest $request)
    {
        $plain_password = strtoupper(Str::random(8));

        DB::beginTransaction();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'category' => $request->category,
            'password' => bcrypt($plain_password),
        ]);
        $user->syncRoles($request->roles);

        DB::commit();

        Mail::to($user)->send(new UserRegisteredMail($user, $plain_password));

        flash('User created successfully!')->success();

        return redirect()->back();
    }

    public function update(Request $request, User $user)
    {
        DB::beginTransaction();

        $user->update($request->only(['name', 'email', 'category']));

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        DB::commit();

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

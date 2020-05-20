<?php

namespace App\Http\Controllers\Staff;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreUserRequest;
use App\Http\Requests\Staff\UpdateUserRequest;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

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
            'categories' => UserCategory::values(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();

        $user = User::create($request->validated() + [
            'password' => bcrypt(Str::random(16)), // Random password
        ]);

        $user->syncRoles($request->roles);

        DB::commit();

        flash('User created successfully!')->success();

        event(new UserCreated($user));

        return redirect()->back();
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();

        $user->update($request->validated());

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

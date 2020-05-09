<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreUserRequest;
use App\Http\Requests\Staff\UpdateUserRequest;
use App\Mail\UserRegisteredMail;
use App\Models\Cosupervisor;
use App\Models\User;
use App\Types\UserCategory;
use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
            'types' => UserCategory::values(),
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

        if ($request->is_supervisor) {
            $user->supervisorProfile()->create();
        }

        DB::commit();

        Mail::to($user)->send(new UserRegisteredMail($user, $plain_password));

        flash('User created successfully!')->success();

        return redirect()->back();
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();

        $user->update($request->only(['name', 'email', 'type']));

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        if ($request->is_supervisor && ! $user->isSupervisor()) {
            $user->supervisorProfile()->create();
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

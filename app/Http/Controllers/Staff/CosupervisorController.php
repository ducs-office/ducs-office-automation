<?php

namespace App\Http\Controllers\Staff;

use App\ExternalAuthority;
use App\Http\Controllers\Controller;
use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Http\Request;

class CosupervisorController extends Controller
{
    public function index()
    {
        $users = User::allTeachers()
            ->where('is_supervisor', false)
            ->whereNotIn('users.id', Cosupervisor::select('person_id')->where('is_supervisor', true));

        $externals = ExternalAuthority::query()
            ->whereNotIn(
                'external_authorities',
                Cosupervisor::select('person_id')->where('person_type', ExternalAuthority::class)
            );

        return view('staff.cosupervisors.index', [
            'cosupervisors' => Cosupervisor::all(),
            'users' => $users,
            'externals' => $externals,
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'user_id' => 'sometimes|bail|nullable|integer',
            'external_id' => 'sometimes|bail|nullable|integer',
            'name' => 'required_without_all:user_id,external_id|string',
            'email' => 'required_without_all:user_id,external_id|email|unique:external_authorities',
            'designation' => 'required_without_all:user_id,external_id|string',
            'affiliation' => 'required_without_all:user_id,external_id|string',
        ]);

        if ($request->has('user_id')) {
            $user = User::find($request->user_id);
            $allowedCategories = [UserCategory::COLLEGE_TEACHER, UserCategory::FACULTY_TEACHER];
            abort_unless($user && in_array($user->category, $allowedCategories), 403, 'only teachers can become cosupervisor');
            abort_if($user->isSupervisor(), 403, 'supervisor cannot become cosupervisor');
            Cosupervisor::create([
                'person_type' => User::class,
                'person_id' => $request->user_id,
            ]);
        } else {
            Cosupervisor::create([
                'person_type' => ExternalAuthority::class,
                'person_id' => $request->external_id ?? ExternalAuthority::create($validData)->id,
            ]);
        }

        flash('Co-supervisor added successfully')->success();
        return back();
    }

    public function destroy(Cosupervisor $cosupervisor)
    {
        $cosupervisor->delete();

        flash('Co-supervisor deleted successfully!')->success();

        return back();
    }
}

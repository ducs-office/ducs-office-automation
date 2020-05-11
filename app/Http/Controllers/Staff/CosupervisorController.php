<?php

namespace App\Http\Controllers\Staff;

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
        $users = User::collegeTeachers()
            ->orWhere('category', UserCategory::FACULTY_TEACHER)
            ->whereDoesntHave('supervisorProfile')
            ->whereDoesntHave('cosupervisorProfile');

        return view('staff.cosupervisors.index', [
            'cosupervisors' => Cosupervisor::all(),
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'user_id' => 'sometimes|bail|nullable|integer|exists:users,id',
            'name' => 'required_without:user_id|string',
            'email' => 'required_without:user_id|email',
            'designation' => 'required_without:user_id|string',
            'affiliation' => 'required_without:user_id|string',
        ]);

        if ($request->has('user_id')) {
            $user = User::find($request->user_id);
            $allowedCategories = [UserCategory::COLLEGE_TEACHER, UserCategory::FACULTY_TEACHER];
            abort_unless(in_array($user->category, $allowedCategories), 403, 'only teachers can become cosupervisor');
            abort_if($user->isSupervisor(), 403, 'supervisor cannot become cosupervisor');
        }

        Cosupervisor::create($validData);

        flash('Co-supervisor added successfully')->success();
        return back();
    }

    public function update(Request $request, Cosupervisor $cosupervisor)
    {
        abort_if($cosupervisor->professor !== null, 403, 'Cosupervisor can not be updated');
        $validData = $request->validate([
            'name' => 'sometimes| required| string',
            'email' => 'sometimes| required| email',
            'designation' => 'sometimes| required| string',
            'affiliation' => 'sometimes| required| string',
        ]);

        $cosupervisor->update($validData);

        flash('Co-supervisor updated successfully!')->success();
        return back();
    }

    public function destroy(Cosupervisor $cosupervisor)
    {
        $cosupervisor->delete();

        flash('Co-supervisor deleted successfully!')->success();

        return back();
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Types\UserType;
use Illuminate\Http\Request;

class CosupervisorController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all()->filter(function ($teacher) {
            return ! $teacher->isCosupervisor() && ! $teacher->isSupervisor();
        });

        $faculties = User::where('type', UserType::FACULTY_TEACHER)
            ->get()->filter(function ($faculty) {
                return ! $faculty->isCosupervisor() && ! $faculty->isSupervisor();
            });

        return view('staff.cosupervisors.index', [
            'cosupervisors' => Cosupervisor::all(),
            'teachers' => $teachers,
            'faculties' => $faculties,
        ]);
    }

    public function storeTeacher(Teacher $teacher)
    {
        if ($teacher->isSupervisor()) {
            abort(403, 'A Supervisor can not be added as a Cosupervisor');
        }

        Cosupervisor::create([
            'professor_type' => Teacher::class,
            'professor_id' => $teacher->id,
        ]);

        flash('Co-supervisor added successfully')->success();
        return back();
    }

    public function storeFaculty(User $faculty)
    {
        if ($faculty->isSupervisor() || ! $faculty->type->equals(UserType::FACULTY_TEACHER)) {
            abort(403, 'Cosupervisor can not be added');
        }

        Cosupervisor::create([
            'professor_type' => User::class,
            'professor_id' => $faculty->id,
        ]);

        flash('Co-supervisor added successfully')->success();
        return back();
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required| string',
            'email' => 'required| email',
            'designation' => 'required| string',
            'affiliation' => 'required| string',
        ]);

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

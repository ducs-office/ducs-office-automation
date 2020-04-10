<?php

namespace App\Http\Controllers\Staff;

use App\Cosupervisor;
use App\Http\Controllers\Controller;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;

class CosupervisorController extends Controller
{
    public function index()
    {
        return view('staff.cosupervisors.index', [
            'cosupervisors' => Cosupervisor::all(),
            'teachers' => Teacher::all(),
            'faculties' => User::where('category', 'faculty_teacher')->get(),
        ]);
    }

    public function storeTeacher(Teacher $teacher)
    {
        Cosupervisor::create([
            'name' => $teacher->name,
            'email' => $teacher->email,
            'designation' => $teacher->profile->designation,
            'affiliation' => $teacher->profile->college,
        ]);

        flash('Co-supervisor added successfully')->success();
        return back();
    }

    public function storeFaculty(User $faculty)
    {
        Cosupervisor::create([
            'name' => $faculty->name,
            'email' => $faculty->email,
            'designation' => 'Professor',
            'affiliation' => 'DUCS',
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

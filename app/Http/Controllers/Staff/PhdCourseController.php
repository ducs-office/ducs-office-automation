<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PhdCourse;
use App\Types\PrePhdCourseType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PhdCourseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PhdCourse::class, 'course');
    }

    public function index(Request $request)
    {
        return view('staff.phd_courses.index', [
            'courses' => PhdCourse::paginate(),
            'courseTypes' => PrePhdCourseType::values(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'min:3', 'max:60', 'unique:phd_courses'],
            'name' => ['required', 'min:3', 'max:190'],
            'type' => ['required', Rule::in(PrePhdCourseType::values())],
        ]);

        PhdCourse::create($data);

        flash('PhD Course added successfully!')->success();

        return redirect()->back();
    }

    public function update(Request $request, PhdCourse $course)
    {
        $data = $request->validate([
            'code' => ['sometimes', 'required', 'min:3', 'max:60', Rule::unique('phd_courses')->ignore($course)],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'type' => ['sometimes', 'required', Rule::in(PrePhdCourseType::values())],
        ]);

        $course->update($data);

        flash('PhD Course updated successfully!')->success();

        return redirect()->back();
    }

    public function destroy(PhdCourse $course)
    {
        $course->delete();

        flash('Course deleted successfully!')->success();

        return redirect()->back();
    }
}

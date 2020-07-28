<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StorePhdCourseRequest;
use App\Http\Requests\Staff\UpdatePhdCourseRequest;
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
            'courses' => PhdCourse::orderBy('created_at', 'DESC')->paginate(15),
            'courseTypes' => PrePhdCourseType::values(),
        ]);
    }

    public function store(StorePhdCourseRequest $request)
    {
        $data = $request->validated();

        PhdCourse::create($data);

        flash('PhD Course added successfully!')->success();

        return redirect()->back();
    }

    public function update(UpdatePhdCourseRequest $request, PhdCourse $course)
    {
        $course->update($request->validated());

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

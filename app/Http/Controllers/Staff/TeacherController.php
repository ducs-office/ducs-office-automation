<?php

namespace App\Http\Controllers\Staff;

use App\Course;
use App\Http\Controllers\Controller;
use App\Mail\UserRegisteredMail;
use App\PastTeachersProfile;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query('filters');
        $query = Teacher::applyFilter($filters)->with([
            'past_profiles',
            'past_profiles.past_teaching_details.course',
            'past_profiles.past_teaching_details.programme_revision.programme',
        ]);

        $teachers = $query->orderBy('id')->get();
        $courses = Course::select('id', 'code', 'name')->get()
            ->map(static function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->code . ' - ' . $course->name,
                ];
            })->pluck('name', 'id');

        $start_date = PastTeachersProfile::getStartDate();
        $end_date = PastTeachersProfile::getEndDate();
        return view('staff.teachers.index', compact('teachers', 'courses', 'start_date', 'end_date'));
    }

    public function show(Request $request, Teacher $teacher)
    {
        $past_profiles = $teacher->past_profiles()->with([
            'past_teaching_details.course',
            'past_teaching_details.programme_revision.programme',
        ]);

        return view('staff.teachers.show', compact('teacher', 'past_profiles'));
    }

    public function avatar(Teacher $teacher)
    {
        $attachmentPicture = $teacher->profile->profile_picture;

        if ($attachmentPicture && Storage::exists($attachmentPicture->path)) {
            return Response::file(Storage::path($attachmentPicture->path));
        }

        $gravatarHash = md5(strtolower(trim(auth()->user()->email)));
        $avatar = file_get_contents('https://gravatar.com/avatar/' . $gravatarHash . '?s=200&d=identicon');

        return Response::make($avatar, 200, [
            'Content-Type' => 'image/jpg',
        ]);
    }

    public function store(Request $request)
    {
        $valid_data = $request->validate([
            'first_name' => 'required| string| min:3| max:50',
            'last_name' => 'required| string| min:3| max:50',
            'email' => 'required| email| unique:teachers| min:3| max:190',
        ]);

        $plain_password = strtoupper(Str::random(8));

        $teacher = Teacher::create($valid_data + ['password' => bcrypt($plain_password)]);

        Mail::to($teacher)->send(new UserRegisteredMail($teacher, $plain_password));

        flash('College Teacher created successfully!')->success();

        return redirect(route('staff.teachers.index'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $valid_data = $request->validate([
            'first_name' => ['sometimes', 'required', 'string', 'min:3', 'max:50'],
            'last_name' => ['sometimes', 'required', 'string', 'min:3', 'max:50'],
            'email' => ['sometimes', 'required', 'min:3', 'max:190', 'email',
                Rule::unique('teachers')->ignore($teacher),
            ],
        ]);

        $teacher->update($valid_data);

        flash('College teacher updated successfully')->success();

        return redirect()->back();
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        flash('College teacher deleted successfully')->success();

        return redirect()->back();
    }
}

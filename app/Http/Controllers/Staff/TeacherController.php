<?php

namespace App\Http\Controllers\Staff;

use App\Course;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserRegisteredMail;
use App\Http\Controllers\Controller;
use App\PastTeachersProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

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

        $Teachers = $query->orderBy('id')->get();
        $courses = Course::select('id', 'code', 'name')->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->code . ' - ' . $course->name,
                ];
            })->pluck('name', 'id');

        $start_date = PastTeachersProfile::getStartDate();
        $end_date = PastTeachersProfile::getEndDate();
        return view('staff.teachers.index', compact('Teachers', 'courses', 'start_date', 'end_date'));
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
            'Content-Type' => 'image/jpg'
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'first_name' => 'required| string| min:3| max:50',
            'last_name' => 'required| string| min:3| max:50',
            'email' => 'required| email| unique:teachers| min:3| max:190',
        ]);

        $plainPassword = strtoupper(Str::random(8));

        $Teacher = Teacher::create($validData + ['password' => bcrypt($plainPassword)]);

        Mail::to($Teacher)->send(new UserRegisteredMail($Teacher, $plainPassword));

        flash('College Teacher created successfully!')->success();

        return redirect(route('staff.teachers.index'));
    }

    public function update(Request $request, Teacher $Teacher)
    {
        $data = $request->validate([
            'first_name' => ['sometimes', 'required', 'string', 'min:3', 'max:50'],
            'last_name' => ['sometimes', 'required', 'string', 'min:3', 'max:50'],
            'email' => ['sometimes', 'required', 'min:3', 'max:190', 'email',
                        Rule::unique('teachers')->ignore($Teacher)
            ]
        ]);

        $Teacher->update($data);

        flash('College teacher updated successfully')->success();

        return redirect()->back();
    }

    public function destroy(Teacher $Teacher)
    {
        $Teacher->delete();

        flash('College teacher deleted successfully')->success();

        return redirect()->back();
    }
}

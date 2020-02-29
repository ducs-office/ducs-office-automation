<?php

namespace App\Http\Controllers\Staff;

use App\Course;
use App\Http\Controllers\Controller;
use App\Mail\UserRegisteredMail;
use App\Teacher;
use App\TeachingRecord;
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
        return view('staff.teachers.index', ([
            'teachers' => Teacher::latest()->get(),
        ]));
    }

    public function show(Request $request, Teacher $teacher)
    {
        $records = $teacher->teachingRecords()
            ->with(['course', 'programmeRevision.programme']);

        return view('staff.teachers.show', ([
            'teacher' => $teacher,
            'records' => $records,
        ]));
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
        $validatedData = $request->validate([
            'first_name' => 'required| string| min:3| max:50',
            'last_name' => 'required| string| min:3| max:50',
            'email' => 'required| email| unique:teachers| min:3| max:190',
        ]);

        $plainPassword = strtoupper(Str::random(8));

        $teacher = Teacher::create($validatedData + ['password' => bcrypt($plainPassword)]);

        Mail::to($teacher)->send(new UserRegisteredMail($teacher, $plainPassword));

        flash('College Teacher created successfully!')->success();

        return redirect(route('staff.teachers.index'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validData = $request->validate([
            'first_name' => ['sometimes', 'required', 'string', 'min:3', 'max:50'],
            'last_name' => ['sometimes', 'required', 'string', 'min:3', 'max:50'],
            'email' => ['sometimes', 'required', 'min:3', 'max:190', 'email',
                Rule::unique('teachers')->ignore($teacher),
            ],
        ]);

        $teacher->update($validData);

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

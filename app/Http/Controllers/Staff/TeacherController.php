<?php

namespace App\Http\Controllers\Staff;

use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserRegisteredMail;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    public function index()
    {
        return view('staff.teachers.index', [
            'teachers' => Teacher::all()
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
                        Rule::unique('teachers')->ignore($teacher)
            ]
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

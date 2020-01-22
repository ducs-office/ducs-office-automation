<?php

namespace App\Http\Controllers;

use App\CollegeTeacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserRegisteredMail;

class CollegeTeacherController extends Controller
{
    public function index()
    {
        $collegeTeachers = CollegeTeacher::all();

        return view('college_teachers.index', compact('collegeTeachers'));
    }

    
    public function store(Request $request)
    {
        $validData = $request->validate([
            'first_name' => 'required| string| min:3| max:50',
            'last_name' => 'required| string| min:3| max:50',
            'email' => 'required| email| unique:college_teachers| min:3| max:190',
        ]);

        $plainPassword = strtoupper(Str::random(8));

        $collegeTeacher = CollegeTeacher::create($validData + ['password' => bcrypt($plainPassword)]);
        
        Mail::to($collegeTeacher)->send(new UserRegisteredMail($collegeTeacher, $plainPassword));

        flash('College Teacher created successfully!')->success();

        return redirect('/college-teachers');
    }

    public function update(Request $request, CollegeTeacher $collegeTeacher)
    {
        $data = $request->validate([
            'first_name' => ['sometimes', 'required', 'string', 'min:3', 'max:50'],
            'last_name' => ['sometimes', 'required', 'string', 'min:3', 'max:50'],
            'email' => ['sometimes', 'required', 'min:3', 'max:190', 'email',
                        Rule::unique('college_teachers')->ignore($collegeTeacher)
            ]
        ]);

        $collegeTeacher->update($data);

        flash('College teacher updated successfully')->success();

        return redirect()->back();
    }

    public function destroy(CollegeTeacher $collegeTeacher)
    {
        $collegeTeacher->delete();

        flash('College teacher deleted successfully')->success();

        return redirect()->back();
    }
}

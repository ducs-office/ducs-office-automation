<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Mail\UserRegisteredMail;
use App\Models\PhdCourse;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response as Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ScholarController extends Controller
{
    public function index()
    {
        return view('staff.scholars.index', [
            'scholars' => Scholar::all(),
        ]);
    }

    public function avatar(Scholar $scholar)
    {
        $attachmentPicture = $scholar->profile->profilePicture;

        if ($attachmentPicture && Storage::exists($attachmentPicture->path)) {
            return Response::file(Storage::path($attachmentPicture->path));
        }

        $gravatarHash = md5(strtolower(trim($scholar->email)));
        $avatar = file_get_contents('https://gravatar.com/avatar/' . $gravatarHash . '?s=200&d=identicon');

        return Response::make($avatar, 200, [
            'Content-Type' => 'image/jpg',
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required| unique:scholars',
        ]);

        $plainPassword = Str::random(8);

        $scholar = Scholar::create($validData + ['password' => bcrypt($plainPassword)]);

        Mail::to($scholar)->send(new UserRegisteredMail($scholar, $plainPassword));

        flash('New scholar added succesfully!')->success();

        return redirect(route('staff.scholars.index'));
    }

    public function update(Request $request, Scholar $scholar)
    {
        $validData = $request->validate([
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'email' => 'sometimes|required|' . Rule::unique('scholars')->ignore($scholar),
        ]);

        $scholar->update($validData);

        flash('Scholar updated successfully!')->success();

        return redirect()->back();
    }

    public function destroy(Scholar $scholar)
    {
        $scholar->delete();

        flash('Scholar deleted successfully!')->success();

        return redirect(route('staff.scholars.index'));
    }
}

<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Scholar;
use App\Types\LeaveStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ScholarLeavesController extends Controller
{
    public function recommend(Request $request, Scholar $scholar, Leave $leave)
    {
        $this->authorize('recommend', $leave);

        $leave->update([
            'status' => LeaveStatus::RECOMMENDED,
        ]);

        flash('Leave was recommended!')->success();

        return redirect()->back();
    }

    public function respond(Scholar $scholar, Leave $leave, Request $request)
    {
        $this->authorize('respond', $leave);

        $request->validate([
            'response' => ['required', Rule::in(LeaveStatus::values())],
            'response_letter' => ['required', 'file', 'mimetypes:application/pdf,image/*', 'max:200'],
        ]);

        $leave->update([
            'status' => $request->response,
            'response_letter_path' => $request->file('response_letter')->store('scholar_leaves/response_letters'),
        ]);

        flash('Leave ' . $request->response . ' successfully!')->success();

        return redirect()->back();
    }

    public function viewApplication(Scholar $scholar, Leave $leave)
    {
        $this->authorize('view', $scholar);

        return Response::download(
            Storage::path($leave->application_path),
            Str::after($leave->application_path, 'scholar_leaves/'),
            [],
            'inline'
        );
    }

    public function viewResponseLetter(Scholar $scholar, Leave $leave)
    {
        $this->authorize('view', $scholar);

        return Response::download(
            Storage::path($leave->response_letter_path),
            Str::after($leave->response_letter_path, 'scholar_leaves/response_letters/'),
            [],
            'inline'
        );
    }
}

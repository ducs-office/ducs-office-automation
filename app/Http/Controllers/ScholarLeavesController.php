<?php

namespace App\Http\Controllers;

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
    public function store(Request $request, Scholar $scholar)
    {
        $this->authorize('create', [Leave::class, $scholar]);

        $rules = [
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after:from'],
            'reason' => ['required', 'string'],
            'application' => ['required', 'file', 'mimetypes:application/pdf,image/*', 'max:200'],
            'extended_leave_id' => ['sometimes', 'nullable', 'exists:leaves,id'],
        ];

        if ($request->reason === 'Other') {
            $rules['reason_text'] = ['required', 'bail', 'string', 'min:5'];
        }
        $request->validate($rules);

        $scholar->leaves()->create([
            'from' => $request->from,
            'to' => $request->to,
            'reason' => $request->reason === 'Other' ? $request->reason_text : $request->reason,
            'application_path' => $request->file('application')->store('scholar_leaves'),
            'extended_leave_id' => $request->extended_leave_id,
        ]);

        return redirect()->back();
    }

    public function recommend(Request $request, Scholar $scholar, Leave $leave)
    {
        $this->authorize('recommend', [$leave, $scholar]);

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
            'response' => ['required', Rule::in([LeaveStatus::APPROVED, LeaveStatus::REJECTED])],
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
        $this->authorize('view', [$leave, $scholar]);

        return Response::download(
            Storage::path($leave->application_path),
            Str::after($leave->application_path, 'scholar_leaves/'),
            [],
            'inline'
        );
    }

    public function viewResponseLetter(Scholar $scholar, Leave $leave)
    {
        $this->authorize('view', [$leave, $scholar]);

        return Response::download(
            Storage::path($leave->response_letter_path),
            Str::after($leave->response_letter_path, 'scholar_leaves/response_letters/'),
            [],
            'inline'
        );
    }
}

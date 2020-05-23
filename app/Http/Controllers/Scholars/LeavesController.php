<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LeavesController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create', [Leave::class, $request->user()]);

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

        $request->user()->leaves()->create([
            'from' => $request->from,
            'to' => $request->to,
            'reason' => $request->reason === 'Other' ? $request->reason_text : $request->reason,
            'application_path' => $request->file('application')->store('scholar_leaves'),
            'extended_leave_id' => $request->extended_leave_id,
        ]);

        return redirect()->back();
    }

    public function viewApplication(Leave $leave)
    {
        abort_unless($leave->scholar->id === auth()->id(), 403, 'You cannot view this file!');

        return Response::download(
            Storage::path($leave->application_path),
            Str::after($leave->application_path, '/'),
            [],
            'inline'
        );
    }

    public function viewResponseLetter(Leave $leave)
    {
        abort_unless($leave->scholar->id === auth()->id(), 403, 'You cannot view this file!');

        return Response::download(
            Storage::path($leave->response_letter_path),
            Str::after($leave->response_letter_path, 'scholar_leaves/response_letters/'),
            [],
            'inline'
        );
    }
}

<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LeavesController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after:from'],
            'reason' => ['required', 'string'],
            'document' => ['required', 'file', 'mimetypes:application/pdf,image/*', 'max:200'],
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
            'document_path' => $request->file('document')->store('scholar_leaves'),
            'extended_leave_id' => $request->extended_leave_id,
        ]);

        return redirect()->back();
    }

    public function attachment(Leave $leave)
    {
        abort_unless($leave->scholar_id === auth()->id(), 403, 'You cannot view this file!');

        return Response::download(
            Storage::path($leave->document_path),
            str_after($leave->document_path, '/'),
            [],
            'inline'
        );
    }
}

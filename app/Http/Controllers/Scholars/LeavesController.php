<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeavesController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after:from'],
            'reason' => ['required', 'string'],
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
            'extended_leave_id' => $request->extended_leave_id,
        ]);

        return redirect()->back();
    }
}

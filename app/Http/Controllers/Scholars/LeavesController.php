<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeavesController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date'],
            'reason' => ['required', 'string'],
            'extended_leave_id' => ['sometimes', 'nullable', 'exists:leaves,id'],
        ]);

        $request->user()->leaves()->create($data);

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Leave;
use App\LeaveStatus;
use App\Scholar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScholarLeavesController extends Controller
{
    public function update(Request $request, Scholar $scholar, Leave $leave)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(LeaveStatus::values())],
        ]);

        $leave->update([
            'status' => new LeaveStatus($request->status),
        ]);

        flash('Leave ' . $request->status . ' successfully!')->success();

        return redirect()->back();
    }
}

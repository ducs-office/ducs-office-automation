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
    public function recommend(Request $request, Scholar $scholar, Leave $leave)
    {
        $this->authorize('recommend', $leave);

        $leave->update([
            'status' => LeaveStatus::RECOMMENDED,
        ]);

        flash('Leave was recommended!')->success();

        return redirect()->back();
    }

    public function approve(Request $request, Scholar $scholar, Leave $leave)
    {
        $this->authorize('approve', $leave);

        $leave->update([
            'status' => LeaveStatus::APPROVED,
        ]);

        flash('Leave approved successfully!')->success();

        return redirect()->back();
    }

    public function reject(Request $request, Scholar $scholar, Leave $leave)
    {
        $this->authorize('reject', $leave);

        $leave->update([
            'status' => LeaveStatus::REJECTED,
        ]);

        flash('Leave rejected successfully!')->success();

        return redirect()->back();
    }
}

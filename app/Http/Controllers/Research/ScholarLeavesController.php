<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveStatus;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
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

    public function approve(Scholar $scholar, Leave $leave)
    {
        $this->authorize('approve', $leave);

        $leave->update([
            'status' => LeaveStatus::APPROVED,
        ]);

        flash('Leave approved successfully!')->success();

        return redirect()->back();
    }

    public function reject(Scholar $scholar, Leave $leave)
    {
        $this->authorize('reject', $leave);

        $leave->update([
            'status' => LeaveStatus::REJECTED,
        ]);

        flash('Leave rejected successfully!')->success();

        return redirect()->back();
    }

    public function viewAttachment(Scholar $scholar, Leave $leave)
    {
        $this->authorize('view', $scholar);

        return Response::download(
            Storage::path($leave->document_path),
            str_after($leave->document_path, '/'),
            [],
            'inline'
        );
    }
}

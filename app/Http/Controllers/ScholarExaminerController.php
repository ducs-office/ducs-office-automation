<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Types\RequestStatus;
use Illuminate\Http\Request;

class ScholarExaminerController extends Controller
{
    public function apply(Request $request, Scholar $scholar)
    {
        $this->authorize('applyForExaminer', $scholar);

        $scholar->update([
            'examiner_status' => RequestStatus::APPLIED,
            'examiner_applied_on' => now(),
        ]);

        flash('Applied for Scholar\'s Examiner Successfully!')->success();

        return redirect()->back();
    }

    public function recommend(Request $request, Scholar $scholar)
    {
        $this->authorize('recommendExaminer', $scholar);

        $scholar->update([
            'examiner_status' => RequestStatus::RECOMMENDED,
            'examiner_recommended_on' => now(),
        ]);

        flash('Examiner request recommended successfully!')->success();

        return redirect()->back();
    }

    public function approve(Request $request, Scholar $scholar)
    {
        $this->authorize('approveExaminer', $scholar);

        $scholar->update([
            'examiner_status' => RequestStatus::APPROVED,
            'examiner_approved_on' => now(),
        ]);

        flash('Examiner request approved successfully!')->success();

        return redirect()->back();
    }
}

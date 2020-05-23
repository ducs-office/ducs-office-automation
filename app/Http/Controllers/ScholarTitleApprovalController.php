<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Models\TitleApproval;
use App\Types\RequestStatus;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use Illuminate\Http\Request;

class ScholarTitleApprovalController extends Controller
{
    public function request(Request $request, Scholar $scholar)
    {
        $this->authorize('apply', [TitleApproval::class, $scholar]);

        return view('research.scholars.title-approval-form', [
            'scholar' => $scholar,
        ]);
    }

    public function apply(Request $request, Scholar $scholar)
    {
        $this->authorize('apply', [TitleApproval::class, $scholar]);

        $scholar->titleApproval()->create([
            'status' => RequestStatus::APPLIED,
        ]);

        flash('Request for Title Approval applied successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function show(Request $request, Scholar $scholar, TitleApproval $appeal)
    {
        $this->authorize('view', [TitleApproval::class, $scholar, $appeal]);

        return view('research.scholars.title-approval-form', [
            'scholar' => $scholar,
        ]);
    }

    public function recommend(Request $request, Scholar $scholar, TitleApproval $appeal)
    {
        $this->authorize('recommend', [TitleApproval::class, $scholar, $appeal]);

        $appeal->update(['status' => RequestStatus::RECOMMENDED]);

        flash("Scholar's appeal recommended successfully!")->success();

        return redirect()->back();
    }

    public function approve(Request $request, Scholar $scholar, TitleApproval $appeal)
    {
        $this->authorize('approve', [TitleApproval::class, $scholar, $appeal]);

        $request->validate([
            'recommended_title' => ['required', 'string'],
        ]);

        $appeal->update([
            'recommended_title' => $request->recommended_title,
            'status' => RequestStatus::APPROVED,
        ]);

        flash("Scholar's appeal approved successfully!")->success();

        return redirect()->back();
    }
}

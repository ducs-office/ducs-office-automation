<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use Illuminate\Http\Request;

class ScholarTitleApprovalController extends Controller
{
    public function request(Request $request, Scholar $scholar)
    {
        $this->authorize('requestTitleApproval', ScholarAppeal::class);

        return view('research.scholars.title-approval-form', [
            'scholar' => $scholar,
        ]);
    }

    public function apply(Request $request, Scholar $scholar)
    {
        $this->authorize('applyTitleApproval', ScholarAppeal::class);

        $scholar->appeals()->create([
            'type' => ScholarAppealTypes::TITLE_APPROVAL,
        ]);

        flash('Request for Pre-PhD Seminar applied successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function show(Request $request, Scholar $scholar)
    {
        $this->authorize('viewTitleApprovalForm', [ScholarAppeal::class, $scholar]);

        return view('research.scholars.title-approval-form', [
            'scholar' => $scholar,
        ]);
    }

    public function approve(Request $request, Scholar $scholar, ScholarAppeal $appeal)
    {
        $this->authorize('respond', $appeal);

        $appeal->update([
            'status' => ScholarAppealStatus::APPROVED,
        ]);

        flash("Scholar's appeal approved successfully!")->success();

        return redirect()->back();
    }

    public function markComplete(Request $request, Scholar $scholar, ScholarAppeal $appeal)
    {
        $this->authorize('markComplete', $appeal);

        $request->validate([
            'recommended_title' => ['required', 'string'],
        ]);

        $appeal->update([
            'status' => ScholarAppealStatus::COMPLETED,
        ]);

        $scholar->update([
            'recommended_title' => $request->recommended_title,
            'title_recommended_on' => now(),
        ]);
        dd($appeal->status);
        flash("Scholar's appeal marked completed successfully!")->success();

        return redirect()->back();
    }
}

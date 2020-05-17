<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScholarAppealController extends Controller
{
    public function showPhdSeminarForm(Scholar $scholar)
    {
        $this->authorize('viewPhdSeminarForm', [ScholarAppeal::class, $scholar]);

        return view('research.scholars.pre_phd_form', [
            'scholar' => $scholar,
        ]);
    }

    public function storePhDSeminar(Scholar $scholar)
    {
        $this->authorize('createPhDSeminar', ScholarAppeal::class);

        $scholar->appeals()->create([
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        flash('Request for Pre-PhD Seminar applied successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function reject(Request $request, Scholar $scholar, ScholarAppeal $appeal)
    {
        $this->authorize('respond', $appeal);

        $appeal->update([
            'status' => ScholarAppealStatus::REJECTED,
            'proposed_title' => $scholar->proposed_title,
        ]);

        $scholar->update([
            'proposed_title' => null,
        ]);

        flash("Scholar's appeal rejected successfully!")->success();

        return redirect()->back();
    }

    public function approve(Request $request, Scholar $scholar, ScholarAppeal $appeal)
    {
        $this->authorize('respond', $appeal);

        $appeal->update([
            'status' => ScholarAppealStatus::APPROVED,
            'proposed_title' => $scholar->proposed_title,
        ]);

        flash("Scholar's appeal approved successfully!")->success();

        return redirect()->back();
    }

    public function markComplete(Request $request, Scholar $scholar, ScholarAppeal $appeal)
    {
        $this->authorize('markComplete', $appeal);

        $appeal->update([
            'status' => ScholarAppealStatus::COMPLETED,
        ]);

        $validData = $request->validate([
            'finalized_title' => ['required', 'string'],
            'title_finalized_on' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $scholar->update($validData);

        flash("Scholar's appeal marked completed successfully!")->success();

        return redirect()->back();
    }
}

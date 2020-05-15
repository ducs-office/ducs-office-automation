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
            'applied_on' => now()->format('Y-m-d'),
            'status' => ScholarAppealStatus::APPLIED,
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        return redirect(route('scholars.profile'));
    }

    public function recommend(Request $request, Scholar $scholar, ScholarAppeal $appeal)
    {
        $this->authorize('recommend', $appeal);

        $appeal->update([
            'status' => ScholarAppealStatus::RECOMMENDED,
        ]);

        flash("Scholar's appeal recommended successfully!")->success();

        return redirect()->back();
    }

    public function respond(Request $request, Scholar $scholar, ScholarAppeal $appeal)
    {
        $this->authorize('respond', $appeal);

        $request->validate([
            'response' => ['required', Rule::in([ScholarAppealStatus::APPROVED, ScholarAppealStatus::REJECTED])],
        ]);

        $appeal->update([
            'status' => $request->response,
        ]);

        flash("Scholar's appeal {$request->response} successfully!")->success();

        return redirect()->back();
    }

    public function reject(Request $request, Scholar $scholar, ScholarAppeal $appeal)
    {
        $this->authorize('respond', $appeal);

        $appeal->update([
            'status' => ScholarAppealStatus::REJECTED,
            'response_date' => now()->format('Y-m-d'),
        ]);

        flash("Scholar's appeal {ScholarAppealStatus::REJECTED} successfully!")->success();

        return redirect()->back();
    }

    public function approve(Request $request, Scholar $scholar, ScholarAppeal $appeal)
    {
        $this->authorize('respond', $appeal);

        $appeal->update([
            'status' => ScholarAppealStatus::APPROVED,
            'response_date' => now()->format('Y-m-d'),
        ]);

        flash("Scholar's appeal {ScholarAppealStatus::APPROVED} successfully!")->success();

        return redirect()->back();
    }
}

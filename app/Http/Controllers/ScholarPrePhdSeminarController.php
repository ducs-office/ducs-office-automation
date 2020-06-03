<?php

namespace App\Http\Controllers;

use App\Models\PrePhdSeminar;
use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Types\RequestStatus;
use App\Types\ScholarAppealTypes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScholarPrePhdSeminarController extends Controller
{
    public function request(Scholar $scholar)
    {
        $this->authorize('create', [PrePhdSeminar::class, $scholar]);

        return view('research.scholars.pre_phd_form', [
            'scholar' => $scholar,
        ]);
    }

    public function show(Scholar $scholar, PrePhdSeminar $prePhdSeminar)
    {
        $this->authorize('view', [$prePhdSeminar, $scholar]);

        return view('research.scholars.pre_phd_form', [
            'scholar' => $scholar,
        ]);
    }

    public function apply(Scholar $scholar)
    {
        $this->authorize('create', [PrePhdSeminar::class, $scholar]);

        $scholar->prePhdSeminar()->create([
            'status' => RequestStatus::APPLIED,
        ]);

        flash('Request for Pre-PhD Seminar applied successfully!')->success();

        return redirect()->back();
    }

    public function forward(Request $request, Scholar $scholar, PrePhdSeminar $prePhdSeminar)
    {
        $this->authorize('forward', [$prePhdSeminar, $scholar]);

        $prePhdSeminar->update([
            'status' => RequestStatus::RECOMMENDED,
        ]);

        flash("Scholar's appeal forwarded successfully!")->success();

        return redirect()->back();
    }

    public function schedule(Request $request, Scholar $scholar, PrePhdSeminar $prePhdSeminar)
    {
        $this->authorize('addSchedule', [$prePhdSeminar, $scholar]);

        $validSchedule = $request->validate([
            'scheduled_on' => ['required', 'date', 'after:today'],
        ]);

        $prePhdSeminar->update($validSchedule);

        flash('Pre PhD seminar schedule added successfully!')->success();

        return redirect()->back();
    }

    public function finalize(Request $request, Scholar $scholar, PrePhdSeminar $prePhdSeminar)
    {
        $this->authorize('finalize', [$prePhdSeminar, $scholar]);

        $validData = $request->validate([
            'finalized_title' => ['required', 'string'],
        ]);

        $prePhdSeminar->update($validData + ['status' => RequestStatus::APPROVED]);

        flash("Scholar's appeal finalized successfully!")->success();

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Models\TitleApproval;
use App\Types\RequestStatus;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use Illuminate\Http\Request;

class TitleApprovalController extends Controller
{
    public function index(Scholar $scholar)
    {
        $this->authorize('viewAny', [TitleApproval::class, $scholar]);

        return view('title-approval.index', [
            'scholar' => $scholar->load('titleApproval'),
        ]);
    }

    public function request(Request $request, Scholar $scholar)
    {
        $this->authorize('create', [TitleApproval::class, $scholar]);

        return view('title-approval.show', [
            'scholar' => $scholar,
        ]);
    }

    public function apply(Request $request, Scholar $scholar)
    {
        $this->authorize('create', [TitleApproval::class, $scholar]);

        $scholar->titleApproval()->create([
            'status' => RequestStatus::APPLIED,
        ]);

        flash('Request for Title Approval applied successfully!')->success();

        return redirect(route('scholars.title-approval.index', $scholar));
    }

    public function show(Request $request, Scholar $scholar, TitleApproval $titleApproval)
    {
        $this->authorize('view', [$titleApproval, $scholar]);

        return view('title-approval.show', [
            'scholar' => $scholar,
        ]);
    }

    public function recommend(Request $request, Scholar $scholar, TitleApproval $titleApproval)
    {
        $this->authorize('recommend', [$titleApproval, $scholar]);

        $titleApproval->update(['status' => RequestStatus::RECOMMENDED]);

        flash("Scholar's appeal recommended successfully!")->success();

        return redirect(route('scholars.title-approval.index', $scholar));
    }

    public function approve(Request $request, Scholar $scholar, TitleApproval $titleApproval)
    {
        $this->authorize('approve', [$titleApproval, $scholar]);

        $request->validateWithBag('approveTitle', [
            'recommended_title' => ['required', 'string', 'min: 5'],
        ]);

        $titleApproval->update([
            'recommended_title' => $request->recommended_title,
            'status' => RequestStatus::APPROVED,
        ]);

        flash("Scholar's appeal approved successfully!")->success();

        return redirect(route('scholars.title-approval.index', $scholar));
    }
}

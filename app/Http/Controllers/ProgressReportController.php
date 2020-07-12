<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProgressReport;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Types\ProgressReportRecommendation;
use App\Types\ScholarDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response as Response;
use Illuminate\Support\Facades\Storage;

class ProgressReportController extends Controller
{
    public function __construct()
    {
        return $this->authorizeResource(ProgressReport::class, 'report');
    }

    public function index(Scholar $scholar)
    {
        return view('progress-report', [
            'scholar' => $scholar->load('progressReports'),
            'recommendations' => ProgressReportRecommendation::values(),
        ]);
    }

    public function store(Request $request, Scholar $scholar)
    {
        $recommendations = implode(',', ProgressReportRecommendation::values());

        $request->validate([
            'progress_report' => ['required', 'file', 'mimetypes:application/pdf,image/*', 'max:200'],
            'recommendation' => ['required', 'in:' . $recommendations],
            'date' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $scholar->progressReports()->create([
            'path' => $request->file('progress_report')->store('progress_reports'),
            'recommendation' => $request->input('recommendation'),
            'date' => $request->input('date'),
        ]);

        flash('Progress Report added successfully!')->success();

        return back();
    }

    public function show(Scholar $scholar, ProgressReport $report)
    {
        abort_if($scholar->id !== $report->scholar->id, 404);

        return Response::file(Storage::path($report->path));
    }

    public function destroy(Scholar $scholar, ProgressReport $report)
    {
        abort_if($scholar->id !== $report->scholar->id, 404);

        $report->delete();

        flash('Progress Report deleted successfully!')->success();

        return back();
    }
}

<?php

namespace App\Http\Controllers\Scholars;

use App\AcademicDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\StorePresentation;
use App\Http\Requests\Scholar\UpdatePresentation;
use App\Presentation;
use App\SupervisorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PresentationController extends Controller
{
    public function create(Request $request)
    {
        $scholar = $request->user();

        return view('scholars.presentations.create', [
            'publications' => $scholar->publications,
            'eventTypes' => config('options.scholars.academic_details.event_types'),
        ]);
    }

    public function store(StorePresentation $request)
    {
        $scholar = $request->user();

        $validData = $request->validated();

        $scholar->presentations()->create($validData);

        flash('Presentation created successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function edit(Request $request, Presentation $presentation)
    {
        $scholar = $request->user();
        if ($presentation->publication->scholar_id == $scholar->id) {
            return view('scholars.presentations.edit', [
                'presentation' => $presentation,
                'publications' => $scholar->publications,
                'eventTypes' => config('options.scholars.academic_details.event_types'),
            ]);
        } else {
            abort(403, 'You are not authorized to edit this presentation');
            return back();
        }
    }

    public function update(UpdatePresentation $request, Presentation $presentation)
    {
        $presentation->update($request->validated());

        flash('Presentation updated successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function destroy(Request $request, Presentation $presentation)
    {
        $scholar = $request->user();

        if ($presentation->publication->scholar_id == $scholar->id) {
            $presentation->delete();

            flash('Presentation deleted successfully!')->success();
        } else {
            abort(403, 'You are not authorized to delete this presentation');
        }

        return back();
    }
}

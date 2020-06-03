<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\StorePresentation;
use App\Http\Requests\Scholar\UpdatePresentation;
use App\Models\Presentation;
use App\Types\PresentationEventType;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function __construct()
    {
        return $this->authorizeResource(Presentation::class, 'presentation');
    }

    public function create(Request $request)
    {
        $scholar = $request->user();

        return view('scholars.presentations.create', [
            'publications' => $scholar->publications,
            'eventTypes' => PresentationEventType::values(),
        ]);
    }

    public function store(StorePresentation $request)
    {
        $scholar = $request->user();
        $validData = $request->validated();

        $scholar->presentations()->create($validData);

        flash('Presentation created successfully!')->success();

        return redirect()->back();
    }

    public function edit(Request $request, Presentation $presentation)
    {
        $scholar = $request->user();

        return view('scholars.presentations.edit', [
            'presentation' => $presentation,
            'publications' => $scholar->publications,
            'eventTypes' => PresentationEventType::values(),
        ]);
    }

    public function update(UpdatePresentation $request, Presentation $presentation)
    {
        $presentation->update($request->validated());

        flash('Presentation updated successfully!')->success();

        return redirect()->back();
    }

    public function destroy(Request $request, Presentation $presentation)
    {
        $presentation->delete();
        flash('Presentation deleted successfully!')->success();

        return back();
    }
}

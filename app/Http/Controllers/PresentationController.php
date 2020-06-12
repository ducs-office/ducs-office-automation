<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\StorePresentation;
use App\Http\Requests\Scholar\UpdatePresentation;
use App\Models\Presentation;
use App\Models\Scholar;
use App\Types\PresentationEventType;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function __construct()
    {
        return $this->authorizeResource(Presentation::class, 'presentation');
    }

    public function index(Scholar $scholar)
    {
        return view('presentations.index', [
            'scholar' => $scholar->load('presentations.publication'),
            'eventTypes' => PresentationEventType::values(),
        ]);
    }

    public function create(Request $request, Scholar $scholar)
    {
        return view('presentations.create', [
            'scholar' => $scholar->load('publications'),
            'eventTypes' => PresentationEventType::values(),
        ]);
    }

    public function store(StorePresentation $request, Scholar $scholar)
    {
        $validData = $request->validated();

        $scholar->presentations()->create($validData);

        flash('Presentation created successfully!')->success();

        return redirect()->route('scholars.presentations.index', $scholar);
    }

    public function edit(Request $request, Scholar $scholar, Presentation $presentation)
    {
        return view('presentations.edit', [
            'presentation' => $presentation,
            'scholar' => $scholar->load('publications'),
            'eventTypes' => PresentationEventType::values(),
        ]);
    }

    public function update(UpdatePresentation $request, Scholar $scholar, Presentation $presentation)
    {
        $presentation->update($request->validated());

        flash('Presentation updated successfully!')->success();

        return redirect()->route('scholars.presentations.index', $scholar);
    }

    public function destroy(Request $request, Scholar $scholar, Presentation $presentation)
    {
        $presentation->delete();

        flash('Presentation deleted successfully!')->success();

        return back();
    }
}

<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\StoreConferencePublication;
use App\Http\Requests\Scholar\UpdateConferencePublication;
use App\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ConferencePublicationController extends Controller
{
    public function create()
    {
        return view('scholars.publications.conferences.create', [
            'indexedIn' => config('options.scholars.academic_details.indexed_in'),
            'months' => config('options.scholars.academic_details.months'),
            'currentYear' => now()->format('Y'),
        ]);
    }

    public function store(StoreConferencePublication $request)
    {
        $scholar = $request->user();

        $validData = $request->validated();
        $date = $validData['date']['month'] . ' ' . $validData['date']['year'];

        $validData['type'] = 'conference';
        $validData['date'] = new Carbon($date);

        $scholar->publications()->create($validData);

        flash('Conference Publication added successfully')->success();

        return redirect(route('scholars.profile'));
    }

    public function edit(Publication $conference)
    {
        return view('scholars.publications.conferences.edit', [
            'conference' => $conference,
            'indexedIn' => config('options.scholars.academic_details.indexed_in'),
            'months' => config('options.scholars.academic_details.months'),
            'currentYear' => now()->format('Y'),
        ]);
    }

    public function update(UpdateConferencePublication $request, Publication $conference)
    {
        $validData = $request->validated();

        $date = $validData['date']['month'] . ' ' . $validData['date']['year'];

        $validData['date'] = new Carbon($date);

        $conference->update($validData);

        flash('Conference Publication updated successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function destroy(Publication $conference)
    {
        $conference->delete();

        flash('Conference Publication deleted successfully!')->success();

        return back();
    }
}

<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\StoreJournalPublication;
use App\Http\Requests\Scholar\UpdateJournalPublication;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class JournalPublicationController extends Controller
{
    public function create()
    {
        return view('scholars.publications.journals.create', [
            'indexedIn' => config('options.scholars.academic_details.indexed_in'),
            'months' => config('options.scholars.academic_details.months'),
            'currentYear' => now()->format('Y'),
        ]);
    }

    public function store(StoreJournalPublication $request)
    {
        $scholar = $request->user();

        $validData = $request->validated();
        $date = $validData['date']['month'] . ' ' . $validData['date']['year'];

        $validData['type'] = 'journal';
        $validData['date'] = new Carbon($date);

        $scholar->publications()->create($validData);

        flash('Journal Publication added successfully')->success();

        return redirect(route('scholars.profile'));
    }

    public function edit(Publication $journal)
    {
        return view('scholars.publications.journals.edit', [
            'journal' => $journal,
            'indexedIn' => config('options.scholars.academic_details.indexed_in'),
            'months' => config('options.scholars.academic_details.months'),
            'currentYear' => now()->format('Y'),
        ]);
    }

    public function update(UpdateJournalPublication $request, Publication $journal)
    {
        $validData = $request->validated();

        $date = $validData['date']['month'] . ' ' . $validData['date']['year'];
        $validData['date'] = new Carbon($date);

        $journal->update($validData);

        flash('Journal Publication updated successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function destroy(Publication $journal)
    {
        $journal->delete();

        flash('Journal Publication deleted successfully!')->success();

        return back();
    }
}

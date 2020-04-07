<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\StoreJournalPublication;
use App\Http\Requests\Scholar\UpdateJournalPublication;
use App\Publication;
use Illuminate\Http\Request;

class JournalPublicationController extends Controller
{
    public function create()
    {
        return view('scholars.publications.journals.create', [
            'indexedIn' => config('options.scholars.academic_details.indexed_in'),
        ]);
    }

    public function store(StoreJournalPublication $request)
    {
        $scholar = $request->user();

        $validData = $request->validated();

        $validData['type'] = 'journal';

        $scholar->publications()->create($validData);

        flash('Journal Publication added successfully')->success();

        return redirect(route('scholars.profile'));
    }

    public function edit(Publication $journal)
    {
        return view('scholars.publications.journals.edit', [
            'journal' => $journal,
            'indexedIn' => config('options.scholars.academic_details.indexed_in'),
        ]);
    }

    public function update(UpdateJournalPublication $request, Publication $journal)
    {
        $validData = $request->validated();

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

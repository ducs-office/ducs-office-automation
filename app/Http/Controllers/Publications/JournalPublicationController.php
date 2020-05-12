<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publication\StoreJournalPublication;
use App\Http\Requests\Publication\UpdateJournalPublication;
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class JournalPublicationController extends Controller
{
    public function __construct()
    {
        return $this->authorizeResource(Publication::class, 'journal');
    }

    public function create()
    {
        return view('publications.journals.create', [
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
        ]);
    }

    public function store(StoreJournalPublication $request)
    {
        $user = $request->user();

        $validData = $request->validated();
        $date = $validData['date']['month'] . ' ' . $validData['date']['year'];

        $validData['type'] = PublicationType::JOURNAL;
        $validData['date'] = new Carbon($date);

        if (Auth::guard('scholars')->check()) {
            $publication = $user->publications()->create($validData);
        } else {
            $publication = $user->supervisorProfile->publications()->create($validData);
        }

        if ($request->has('co_authors')) {
            $publication->coAuthors()->createMany(
                array_map(static function ($coAuthor) {
                    return [
                        'name' => $coAuthor['name'],
                        'noc_path' => $coAuthor['noc']->store('/publications/co_authors_noc'),
                    ];
                }, $validData['co_authors'])
            );
        }

        flash('Journal Publication added successfully')->success();

        if (Auth::guard('scholars')->check()) {
            return redirect(route('scholars.profile'));
        } else {
            return redirect(route('research.publications.index'));
        }
    }

    public function edit(Publication $journal)
    {
        return view('publications.journals.edit', [
            'journal' => $journal,
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
        ]);
    }

    public function update(UpdateJournalPublication $request, Publication $journal)
    {
        $validData = $request->validated();

        $date = $validData['date']['month'] . ' ' . $validData['date']['year'];
        $validData['date'] = new Carbon($date);

        $journal->update($validData);

        if ($request->has('co_authors')) {
            $journal->coAuthors()->createMany(
                array_map(static function ($coAuthor) {
                    return [
                        'name' => $coAuthor['name'],
                        'noc_path' => $coAuthor['noc']->store('/publications/co_authors_noc'),
                    ];
                }, $validData['co_authors'])
            );
        }

        flash('Journal Publication updated successfully!')->success();

        if (Auth::guard('scholars')->check()) {
            return redirect(route('scholars.profile'));
        } else {
            return redirect(route('research.publications.index'));
        }
    }

    public function destroy(Publication $journal)
    {
        $journal->delete();

        flash('Journal Publication deleted successfully!')->success();

        return back();
    }
}

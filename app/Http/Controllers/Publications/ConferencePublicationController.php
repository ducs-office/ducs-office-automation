<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publication\StoreConferencePublication;
use App\Http\Requests\Publication\UpdateConferencePublication;
use App\Models\Publication;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ConferencePublicationController extends Controller
{
    public function __construct()
    {
        return $this->authorizeResource(Publication::class, 'conference');
    }

    public function create()
    {
        return view('publications.conferences.create', [
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
        ]);
    }

    public function store(StoreConferencePublication $request)
    {
        $user = $request->user();

        $validData = $request->validated();
        $date = $validData['date']['month'] . ' ' . $validData['date']['year'];

        $validData['type'] = PublicationType::CONFERENCE;
        $validData['date'] = new Carbon($date);

        $publication = $user->publications()->create($validData);

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

        flash('Conference Publication added successfully')->success();

        if (Auth::guard('scholars')->check()) {
            return redirect(route('scholars.profile'));
        } else {
            return redirect(route('research.publications.index'));
        }
    }

    public function edit(Publication $conference)
    {
        return view('publications.conferences.edit', [
            'conference' => $conference,
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
        ]);
    }

    public function update(UpdateConferencePublication $request, Publication $conference)
    {
        $validData = $request->validated();

        $date = $validData['date']['month'] . ' ' . $validData['date']['year'];

        $validData['date'] = new Carbon($date);

        $conference->update($validData);

        if ($request->has('co_authors')) {
            $conference->coAuthors()->createMany(
                array_map(static function ($coAuthor) {
                    return [
                        'name' => $coAuthor['name'],
                        'noc_path' => $coAuthor['noc']->store('/publications/co_authors_noc'),
                    ];
                }, $validData['co_authors'])
            );
        }

        flash('Conference Publication updated successfully!')->success();

        if (Auth::guard('scholars')->check()) {
            return redirect(route('scholars.profile'));
        } else {
            return redirect(route('research.publications.index'));
        }
    }

    public function destroy(Publication $conference)
    {
        $conference->delete();

        flash('Conference Publication deleted successfully!')->success();

        return back();
    }
}

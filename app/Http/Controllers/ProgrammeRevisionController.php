<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Programme;
use App\ProgrammeRevision;

class ProgrammeRevisionController extends Controller
{
    public function index(Programme $programme)
    {
        $programmeRevisions = $programme->revisions->sortByDesc('revised_at');

        $groupedRevisionCourses = $programmeRevisions->map(function ($programmeRevision) {
            return $programmeRevision->courses->groupBy('pivot.semester');
        });
        
        return view('programmes.revisions.index', compact('programme', 'programmeRevisions', 'groupedRevisionCourses'));
    }

    public function destroy(Programme $programme, ProgrammeRevision $programmeRevision)
    {
        $programmeRevision->delete();
        
        return redirect("/programme/{$programme->id}/revisions");
    }
}

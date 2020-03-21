<?php

namespace App\Http\Controllers\Scholars;

use App\AcademicDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\StoreAcademicDetail;
use App\Http\Requests\Scholar\UpdateAcademicDetail;
use App\SupervisorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PresentationController extends Controller
{
    public function create()
    {
        return view('scholars.presentations.create', [
            'indexedIn' => config('options.scholars.academic_details.indexed_in'),
        ]);
    }

    public function store(StoreAcademicDetail $request)
    {
        $scholar = $request->user();

        $validData = $request->validated();

        $validData['type'] = 'presentation';

        $scholar->academicDetails()->create($validData);

        flash('Presentation added successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function edit(AcademicDetail $presentation)
    {
        return view('scholars.presentations.edit', [
            'presentation' => $presentation,
            'indexedIn' => config('options.scholars.academic_details.indexed_in'),
        ]);
    }

    public function update(UpdateAcademicDetail $request, AcademicDetail $presentation)
    {
        $scholar = $request->user();

        $validData = $request->validated();

        $presentation->update($validData);

        flash('Presentation updated successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function destroy(AcademicDetail $presentation)
    {
        $presentation->delete();

        flash('Presentation deleted successfully!')->success();

        return back();
    }
}

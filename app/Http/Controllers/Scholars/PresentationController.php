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
    public function store(StoreAcademicDetail $request)
    {
        $scholar = $request->user();

        $validData = $request->validated();

        $validData['type'] = 'presentation';

        $scholar->academicDetails()->create($validData);

        flash('Presentation added successfully!')->success();

        return back();
    }

    public function update(UpdateAcademicDetail $request, AcademicDetail $presentation)
    {
        $scholar = $request->user();

        $validData = $request->validated();

        $presentation->update($validData);

        flash('Presentation updated successfully!')->success();

        return back();
    }

    public function destroy(AcademicDetail $presentation)
    {
        $presentation->delete();

        flash('Presentation deleted successfully!')->success();

        return back();
    }
}

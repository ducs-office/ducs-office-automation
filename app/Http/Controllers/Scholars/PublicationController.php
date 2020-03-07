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

class PublicationController extends Controller
{
    public function store(StoreAcademicDetail $request)
    {
        $scholar = $request->user();

        $validData = $request->validated();

        $validData['type'] = 'publication';

        $scholar->academicDetails()->create($validData);

        flash('Publication added successfully!')->success();

        return back();
    }

    public function update(UpdateAcademicDetail $request, AcademicDetail $publication)
    {
        $scholar = $request->user();

        $validData = $request->validated();

        $publication->update($validData);

        flash('Publication updated successfully!')->success();

        return back();
    }

    public function destroy(AcademicDetail $publication)
    {
        $publication->delete();

        flash('Publication deleted successfully!')->success();

        return back();
    }
}

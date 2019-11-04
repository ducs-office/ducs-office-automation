<?php

namespace App\Http\Controllers;

use App\Programme;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgrammesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programmes = Programme::latest()->get();
        return view('programmes.index', compact('programmes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'min:3', 'max:10', 'unique:programmes,code'],
            'wef' => ['required', 'date'],
            'name' => ['required', 'min:3', 'max:190'],
        ]);

        Programme::create($data);

        flash('Programme created successfully!', 'success');

        return redirect('/programmes');
    }

    public function update(Request $request, Programme $programme)
    {

        $data = $request->validate([
            'code' => [
                'sometimes', 'required', 'min:3', 'max:10',
                Rule::unique('programmes')->ignore($programme)
            ],
            'wef' => ['sometimes', 'required', 'date'],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
        ]);

        $programme->update($data);

        flash('Programme updated successfully!', 'success');

        return redirect('/programmes');
    }

    public function destroy(Programme $programme)
    {
        $programme->delete();

        flash('Programme deleted successfully!', 'success');

        return redirect('/programmes');
    }

}

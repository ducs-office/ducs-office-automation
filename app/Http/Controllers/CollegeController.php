<?php

namespace App\Http\Controllers;

use App\College;
use App\Programme;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CollegeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(College::class, 'college');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colleges = College::all();
        $programmes = Programme::all();

        return view('colleges.index', compact('colleges', 'programmes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required','min:3','max:20','unique:colleges,code'],
            'name' => ['required','min:3','max:100','unique:colleges,name'],
            'programmes' => ['required', 'array', 'min:1'],
            'programmes.*' => ['required', 'integer', 'exists:programmes,id']
        ]);

        $college = College::create($request->only(['code','name']));
        
        $college->programmes()->attach($data['programmes']);

        flash('College created successfully!', 'success');
        
        return redirect('/colleges');
    }


    public function update(Request $request, College $college)
    {
        $validData = $request->validate([
            'code' => [
                'sometimes', 'required', 'min:3', 'max:60',
                Rule::unique('colleges')->ignore($college)
            ],
            'name'=>[
                'sometimes', 'required', 'min:5', 'max:100',
                Rule::unique('colleges')->ignore($college)
            ],
            'programmes' => ['sometimes', 'required', 'array', 'min:1'],
            'programmes.*' => ['sometimes', 'required', 'integer', 'exists:programmes,id']
        ]);

        $college->update($request->only(['code', 'name']));

        if (isset($validData['programmes'])) {
            $college->programmes()->sync($validData['programmes']);
        }

        flash('College updated successfully!', 'success');

        return redirect('/colleges');
    }

    public function destroy(College $college)
    {
        $college->delete();

        flash('College deleted successfully!', 'success');

        return redirect('/colleges');
    }
}

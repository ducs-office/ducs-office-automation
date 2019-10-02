<?php

namespace App\Http\Controllers;

use App\College;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colleges = College::all();

        return view('colleges.index',compact('colleges'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'=>['required','min:3','max:15','unique:colleges,code'],
            'name'=>['required','min:5','max:50','unique:colleges,name']
        ]);

        College::create($data);

        flash('College created successfully!','success');

        return redirect('/colleges');
    }


    public function update(Request $request, College $college)
    {
        
        $validData = $request->validate([
            'code'=>'sometimes|required|min:3|max:15|unique:colleges,code',
            'name'=>'sometimes|required|min:5|max:50|unique:colleges,name'
        ]);
        
        $college->update($validData);
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

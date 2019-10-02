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
            'code'=>['required','min:3','max:15','unique:colleges,codes'],
            'name'=>['required','min:5','max:50','unique:colleges,name']
        ]);

        College::create($data);

        flash('College created successfully!','success');

        return redirect('/colleges');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\College  $college
     * @return \Illuminate\Http\Response
     */
    public function show(College $college)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\College  $college
     * @return \Illuminate\Http\Response
     */
    public function edit(College $college)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\College  $college
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, College $college)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\College  $college
     * @return \Illuminate\Http\Response
     */
    public function destroy(College $college)
    {
        //
    }
}

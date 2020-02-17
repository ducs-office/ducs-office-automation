<?php

namespace App\Http\Controllers\Staff;

use App\College;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreCollegeRequest;
use App\Http\Requests\Staff\UpdateCollegeRequest;
use App\Programme;

class CollegeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(College::class, 'college');
    }

    public function index()
    {
        return view('staff.colleges.index', [
            'colleges' => College::all(),
            'programmes' => Programme::all(),
        ]);
    }

    public function create()
    {
        return view('staff.colleges.create', [
            'programmes' => Programme::all(),
        ]);
    }

    public function store(StoreCollegeRequest $request)
    {
        $college = College::create($data = $request->validated());

        $college->programmes()->attach($data['programmes']);

        flash('College created successfully!', 'success');

        return redirect(route('staff.colleges.index'));
    }

    public function edit(College $college)
    {
        return view('staff.colleges.edit', [
            'college' => $college,
            'programmes' => Programme::all(),
        ]);
    }

    public function update(UpdateCollegeRequest $request, College $college)
    {
        $college->update($request->validated());

        if ($request->has('programmes')) {
            $college->programmes()->sync($request->programmes);
        }

        flash('College updated successfully!', 'success');

        return redirect(route('staff.colleges.index'));
    }

    public function destroy(College $college)
    {
        $college->delete();

        flash('College deleted successfully!', 'success');

        return redirect(route('staff.colleges.index'));
    }
}

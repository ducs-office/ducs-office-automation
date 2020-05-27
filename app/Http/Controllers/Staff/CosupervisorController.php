<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CosupervisorController extends Controller
{
    public function index()
    {
        $nonCosupervisors = User::select(['id', 'first_name', 'last_name'])
            ->nonCosupervisors()
            ->nonSupervisors()
            ->get();
        $currentCosupervisors = User::select(['id', 'first_name', 'last_name'])
            ->cosupervisors()
            ->nonSupervisors()
            ->get();

        return view('staff.cosupervisors.index', [
            'cosupervisors' => $currentCosupervisors,
            'users' => $nonCosupervisors,
        ]);
    }

    public function store(Request $request)
    {
        $allowedCategories = [
            UserCategory::COLLEGE_TEACHER,
            UserCategory::FACULTY_TEACHER,
            UserCategory::EXTERNAL,
        ];

        $request->validate([
            'user_id' => [
                'required', 'integer',
                Rule::exists(User::class, 'id')
                    ->whereIn('category', $allowedCategories)
                    ->where('is_supervisor', 0),
            ],
        ]);

        User::whereId($request->user_id)
            ->update(['is_cosupervisor' => true]);

        flash('Co-supervisor added successfully')->success();
        return back();
    }

    public function destroy(User $cosupervisor)
    {
        $cosupervisor->update(['is_cosupervisor' => false]);

        flash('Co-supervisor deleted successfully!')->success();

        return back();
    }
}

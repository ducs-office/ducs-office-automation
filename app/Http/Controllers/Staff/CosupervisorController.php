<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ExternalAuthority;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CosupervisorController extends Controller
{
    public function index()
    {
        $users = User::select(['id', 'first_name', 'last_name'])->nonCosupervisors()->get();
        $externals = ExternalAuthority::select(['id', 'name'])->nonCosupervisors()->get();

        return view('staff.cosupervisors.index', [
            'cosupervisors' => User::cosupervisors()->get(),
            'externalCosupervisors' => ExternalAuthority::nonCosupervisors()->get(),
            'users' => $users,
            'externals' => $externals,
        ]);
    }

    public function store(Request $request)
    {
        $allowedCategories = [UserCategory::COLLEGE_TEACHER, UserCategory::FACULTY_TEACHER];

        $validData = $request->validate([
            'user_id' => [
                'required', 'integer',
                Rule::exists(User::class, 'id')
                    ->whereIn('category', $allowedCategories)
                    ->where('is_supervisor', 0),
            ],
            // 'external_id' => 'sometimes|bail|nullable|integer',
            // 'name' => 'required_without_all:user_id,external_id|string',
            // 'email' => 'required_without_all:user_id,external_id|email|unique:external_authorities',
            // 'designation' => 'required_without_all:user_id,external_id|string',
            // 'affiliation' => 'required_without_all:user_id,external_id|string',
        ]);

        // if ($request->has('user_id')) {
        User::whereId($request->user_id)
            ->update(['is_cosupervisor' => true]);
        // } elseif ($request->has('external_id')) {
        //     ExternalAuthority::whereId($request->external_id)->update(['is_cosupervisor' => true]);
        // } else {
        //     ExternalAuthority::updateOrCreate(
        //         $validData,
        //         ['is_cosupervisor' => true]
        //     );
        // }

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

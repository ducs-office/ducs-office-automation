<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\MustBeSupervisor;
use Illuminate\Http\Request;

class ScholarsController extends Controller
{
    public function __construct()
    {
        $this->middleware(MustBeSupervisor::class);
    }

    public function index(Request $request)
    {
        $scholars = $request->user()
            ->load('supervisorProfile.scholars')
            ->supervisorProfile
            ->scholars;

        return view('teachers.scholars.index', ['scholars' => $scholars]);
    }
}

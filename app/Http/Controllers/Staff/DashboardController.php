<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('staff.dashboard');
    }
}

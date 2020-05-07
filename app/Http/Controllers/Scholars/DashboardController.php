<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $scholar = $request->user();

        return view('scholars.dashboard', [
            'scholar' => $request->user(),
        ]);
    }
}

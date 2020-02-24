<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $scholar = $request->user()->load(['profile']);

        return view('scholars.profile', [
            'scholar' => $scholar,
            'categories' => config('option.scholars.categories'),
            'admission_via' => config('options.scholars.admission_via'),
        ]);
    }
}

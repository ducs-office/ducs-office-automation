<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowPublications extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $supervisor = $request->user()->load([
            'journals',
            'conferences',
        ]);

        return view('research.publications.index', [
            'supervisor' => $supervisor,
        ]);
    }
}

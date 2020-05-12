<?php

namespace App\Http\Controllers;

use App\ExternalAuthority;
use App\Http\Requests\ExternalAuthorityStoreRequest;
use App\Http\Requests\ExternalAuthorityUpdateRequest;
use Illuminate\Http\Request;

class ExternalAuthorityController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $externalAuthorities = ExternalAuthority::all();

        return view('external-authority.index', compact('externalAuthorities'));
    }

    /**
     * @param \App\Http\Requests\ExternalAuthorityStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ExternalAuthorityStoreRequest $request)
    {
        $externalAuthority = ExternalAuthority::create($request->validated());

        flash($externalAuthority->title . ' was created successfully');

        return redirect()->route('external-authority.index');
    }

    /**
     * @param \App\Http\Requests\ExternalAuthorityStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ExternalAuthorityUpdateRequest $request, ExternalAuthority $externalAuthority)
    {
        $externalAuthority->update($request->validated());

        flash($externalAuthority->title . ' was created successfully');

        return redirect()->route('external-authority.index');
    }
}

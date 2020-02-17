<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->has('q') || trim($request->q) === '') {
            return [];
        }

        $query = User::where('email', 'like', $request->q);

        if ($query->exists()) {
            return $query->limit($request->limit)->get();
        }

        return $query->orWhere('name', 'like', "%{$request->q}%")
            ->limit($request->limit)
            ->get();
    }

    public function show(User $user)
    {
        return $user;
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LogOutController extends Controller
{
    use AuthenticatesUsers;

    public $redirectTo = '/logout';

    public function logout() {
      session()->flush();
      $user = 'Guest';
      return view('welcome',compact('user'));
    }
}

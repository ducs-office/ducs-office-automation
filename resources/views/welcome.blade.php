@extends('layouts.master')
@section('body')
    <h1 class="text-blue-400">Welcome, {{ Auth::check() ? Auth::user()->name : 'Guest'}}</h1>
@endsection
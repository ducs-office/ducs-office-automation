@extends('layouts.research')
@section('body')
    @include('publications.partials.index', [
        'user' => $supervisor->supervisorProfile,
    ])
@endsection
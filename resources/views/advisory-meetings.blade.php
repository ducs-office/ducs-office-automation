{{-- Meetings --}}
@extends('layouts.scholar-profile', ['pageTitle' => 'Advisory Meetings', 'scholar' => $scholar])
@section('body')
    @include('_partials.scholar-profile.advisory-meetings')
@endsection

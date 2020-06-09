@extends('layouts.master', ['pageTitle' => 'Profile'])
@section('body')
    <div class="container mx-auto p-4 space-y-8">
        @include('_partials.research.scholar-profile.basic-info')
        @include('_partials.research.scholar-profile.supervisors-card')
        @include('_partials.research.scholar-profile.cosupervisors-card')
        {{-- @include('_partials.research.scholar-profile.advisory-committee') --}}
        @include('_partials.research.scholar-profile.pre-phd-courseworks')
        @include('_partials.research.scholar-profile.publications')
        @include('_partials.research.scholar-profile.presentations')
        @include('_partials.research.scholar-profile.leaves')
        @include('_partials.research.scholar-profile.advisory-meetings')
        @include('_partials.research.scholar-profile.progress-reports')
        @include('_partials.research.scholar-profile.documents')
        @include('_partials.research.scholar-profile.pre-phd-seminar')
        @include('_partials.research.scholar-profile.title-approval')
        @include('_partials.research.scholar-profile.examiner')
@endsection

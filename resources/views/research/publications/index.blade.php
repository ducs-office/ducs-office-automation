@extends('layouts.research')
@section('body')
    <div class="page-card p-6 overflow-visible space-y-6 m-4">
        @include('research.scholars.publications.journals.index', [
            'journals' => $supervisor->supervisorProfile->journals
        ])

        @include('research.scholars.publications.conferences.index', [
            'conferences' => $supervisor->supervisorProfile->conferences
        ])
    </div>
@endsection

@extends('layouts.research')
@section('body')
    <div class="page-card p-6 overflow-visible space-y-6 m-4">
        @include('_partials.research.journal-publications', [
            'journals' => $supervisor->journals
        ])

        @include('_partials.research.conference-publications', [
            'conferences' => $supervisor->conferences
        ])
    </div>
@endsection

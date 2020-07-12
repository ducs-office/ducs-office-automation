@extends('layouts.scholar-profile', ['scholar' => $scholar])
@section('body')
    <div class="page-card max-w-3xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Create Publication</h2>
        </div>
        @include('_partials.forms.create-publication', [
            'route' => route('scholars.publications.store', $scholar)
        ])
    </div>
@endsection

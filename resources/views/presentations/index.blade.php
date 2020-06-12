@extends('layouts.scholar-profile', ['pageTitle' => 'Presentations', 'scholar' => $scholar])
@section('body')
<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Presentations
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
        @can('create', App\Models\Presentation::class)
        <div class="mt-3 text-right">
            <a class="btn btn-magenta" href="{{ route('scholars.presentations.create', ['scholar' => $scholar]) }}">
                New
            </a>
        </div>
        @endcan
    </div>
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4">
            @forelse ($scholar->presentations as $presentation)
                @include('_partials.list-items.presentation')
            @empty
                <p class="text-gray-600 flex justify-center font-bold py-3 items-center">No presentations to show!</p>
            @endforelse

        </ul>
    </div>
</div>
@endsection

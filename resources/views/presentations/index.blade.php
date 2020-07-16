@extends('layouts.scholar-profile', ['pageTitle' => 'Presentations', 'scholar' => $scholar])
@section('body')
<div class="page-card p-6 space-y-4">
    @can('create', App\Models\Presentation::class)
    <div class="mt-3 text-right">
        <a class="btn btn-magenta" href="{{ route('scholars.presentations.create', ['scholar' => $scholar]) }}">
            New
        </a>
    </div>
    @endcan
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4">
            @forelse ($scholar->presentations as $presentation)
                @include('_partials.list-items.presentation')
            @empty
                <li><p class="text-gray-600 flex justify-center font-bold py-3 items-center">No presentations to show!</p></li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

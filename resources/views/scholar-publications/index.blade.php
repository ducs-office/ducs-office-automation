@extends('layouts.scholar-profile', ['pageTitle' => 'Publications','scholar' => $scholar])
@section('body')
<div class="page-card p-6 overflow-visible space-y-6">
    <div class="flex items-baseline">
        <h1 class="text-2xl font-bold mr-4">
            Publications
        </h1>
        @can('create', [App\Model\Publication::class, $scholar])
        <div class="justify-end flex-1 w-full flex">
            <a href="{{ route('scholars.publications.create', $scholar) }}"
                class="btn btn-magenta">
                    New
            </a>
        </div>
        @endcan
    </div>
    <div class="mt-4">
        <div class="w-64 pr-4 relative -ml-8 my-2">
            <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Journals
            </h3>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>
        <ul class="flex-1 border rounded-lg overflow-hidden my-4">
            @forelse ($scholar->journals as $publication)
            @include('_partials.list-items.publication', [
                'editRoute' => route('scholars.publications.edit', [$scholar, $publication]),
                'deleteRoute' => route('scholars.publications.destroy', [$scholar, $publication])
            ])
            @empty
            <p class="px-4 py-3 text-center text-gray-700 font-bold">Nothing to show here!</p>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">
        <div class="w-64 pr-4 relative -ml-8 my-2">
            <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Conferences
            </h3>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>
        <ul class="flex-1 border rounded-lg overflow-hidden my-4">
            @forelse ($scholar->conferences as $publication)
            @include('_partials.list-items.publication', [
                'editRoute' => route('scholars.publications.edit', [$scholar, $publication]),
                'deleteRoute' => route('scholars.publications.destroy', [$scholar, $publication])
            ])
            @empty
            <p class="px-4 py-3 text-center text-gray-700 font-bold">Nothing to show here!</p>
            @endforelse
        </ul>
    </div>
</div>
@endsection

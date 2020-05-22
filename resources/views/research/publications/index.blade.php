@extends('layouts.research')
@section('body')
    <div class="container mx-auto p-4 space-y-8">
        <div class="page-card p-6 overflow-visible space-y-6">
            <div class="flex items-baseline">
                <div class="w-64 pr-4 relative -ml-8 my-2">
                    <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                    Publications
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                @if(auth()->guard('web')->check() && auth()->guard('web')->id() === $supervisor->id)
                <div class="justify-end flex-1 w-full flex">
                    <a href="{{ route('publications.create') }}"
                        class="btn btn-magenta">
                            New
                    </a>
                </div>
                @endif
            </div>

            <div class="flex items-start space-x-6">
                <div class="w-64 pr-4 relative -ml-8 my-2">
                    <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Journals
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                @include('_partials.research.publications', [
                    'publications' => $supervisor->journals
                ])
            </div>

            <div class="flex items-start space-x-6">
                <div class="w-64 pr-4 relative -ml-8 my-2">
                    <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Conferences
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                @include('_partials.research.publications', [
                    'publications' => $supervisor->conferences
                ])
            </div>
        </div>
    </div>
@endsection

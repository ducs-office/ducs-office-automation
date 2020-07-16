@extends('layouts.master', ['pageTitle' => 'Publications'])
@push('modals')
<livewire:co-authors-modal :error-bag="$errors->createCoAuthor" />
@endpush
@section('body')
    <div class="container mx-auto p-4 space-y-8">
        <div class="page-card p-6 overflow-visible space-y-6" x-data="{compact: false}">
            <div class="flex items-baseline">    
                <h1 class="text-2xl font-bold mr-4">
                    Publications
                </h1>
                @can('create', [App\Model\Publication::class, $user])    
                <div class="justify-end items-center flex-1 w-full flex space-x-4">
                    <button class="btn p-1" 
                        x-bind:class="{'btn-magenta' : compact}"  
                        x-on:click="compact = !compact">
                        <x-feather-icon name="list" class="h-5 w-5"></x-feather-icon>
                    </button>
                    <a href="{{ route('users.publications.create', $user) }}"
                        class="btn btn-magenta py-1">
                            New
                    </a>
                </div>
                @endcan
            </div>
            <template x-if="compact">
                <div class="mt-4">
                    <h3 class="text-xl font-bold mb-2">
                        Journals
                    </h3>
                    <ul class="pl-4 list-disc space-y-1 mb-6">
                        @foreach($user->journals as $journal)
                        <li class="pl-2">{!! strip_tags($journal, '<a>') !!}</li>
                        @endforeach
                    </ul>

                    <h3 class="text-xl font-bold mb-2">
                        Conferences
                    </h3>
                    <ul class="pl-4 list-disc space-y-1 mb-6">
                        @foreach($user->conferences as $conference)
                        <li class="pl-2">{!! strip_tags($conference, '<a>') !!}</li>
                        @endforeach
                    </ul>
                </div>
            </template>
            
            <template x-if="! compact">
            <div class="space-y-4">
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
                        @forelse ($user->journals as $publication)
                        @include('_partials.list-items.publication', [
                            'editRoute' => route('users.publications.edit', [$user, $publication]),
                            'deleteRoute' => route('users.publications.destroy', [$user, $publication]) 
                        ])
                        @empty
                        <li><p class="px-4 py-3 text-center text-gray-700 font-bold">Nothing to show here!</p></li>
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
                        @forelse ($user->conferences as $publication)
                        @include('_partials.list-items.publication', [
                            'editRoute' => route('users.publications.edit', [$user, $publication]),
                            'deleteRoute' => route('users.publications.destroy', [$user, $publication]) 
                        ])
                        @empty
                        <li><p class="px-4 py-3 text-center text-gray-700 font-bold">Nothing to show here!</p></li>
                        @endforelse
                    </ul>
                </div>
            </div>
            </template>
        </div>
    </div>
@endsection

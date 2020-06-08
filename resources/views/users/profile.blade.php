@extends('layouts.master')
@section('body')
<div class="m-4 grid gap-8 grid-cols-2 items-start">
    <div class="col-span-2 page-card p-6 overflow-visible">
        <div class="-mt-6 -mx-6 bg-magenta-800 h-48 rounded-t-md flex justify-end items-end p-4">
            @can('updateProfile', $user)
            <a href="#" class="btn inline-flex">
                <x-feather-icon name="edit" class="h-current mr-2"></x-feather-icon>
                Edit
            </a>
            @endif
        </div>
        <div class="-mt-24 space-y-4 text-center mb-8">
            <img src="{{ $user->avatar_url }}"
                class="flex items-center justify-center w-48 h-48 mx-auto object-cover border-4 border-white bg-white rounded-full shadow-md overflow-hidden"
                alt="{{ $user->name }}'s avatar">
            <div>
                <h2 class="relative text-3xl">
                    {{ $user->name }}
                </h2>
                @if($user->designation)
                    <h3 class="text-xl text-gray-700 mt-2">
                        {{ $user->category }} / {{ $user->designation }}
                    </h3>
                @endif
                @if($user->college)
                    <h4 class="text-lg text-gray-700 italic mt-1 capitalize">
                        {{ $user->college->name }}
                    </h4>
                @endif
            </div>
        </div>
        <x-tabbed-pane current-tab="info">
            <x-slot name="tabs">
                <div class="flex items-center justify-center space-x-3 border-b -mx-6 px-6 mb-6">
                    <x-tab name="info">Basic Info</x-tab>
                    <x-tab name="teaching">Work</x-tab>
                </div>
            </x-slot>

            <x-tab-content tab="info" class="space-y-3 w-full max-w-2xl mx-auto">
                <h3 class="px-3 text-lg font-bold">
                    Basic Information
                </h3>

                <div class="mt-4 flex-1">
                    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
                        <li class="px-4 py-3 flex space-x-4">
                            <h4 class="whitespace-no-wrap font-bold w-48">Email</h4>
                            <p class="flex-1 text-gray-800"> {{ $user->email }}</p>
                        </li>
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">Contact Number</p>
                            <p class="flex-1 text-gray-800">{{ $user->phone ?? '-' }}</p>
                        </li>
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">Address</p>
                            <p class="flex-1 text-gray-800">
                                {{ $user->address ?? '-'}}
                            </p>
                        </li>
                    </ul>
                </div>
            </x-tab-content>

            <x-tab-content tab="teaching" class="space-y-3 w-full max-w-2xl mx-auto">
                <h3 class="px-3 text-lg font-bold">
                    Work Details
                </h3>
                <div class="mt-4 flex-1">
                    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
                        @if($user->isCollegeTeacher() || $user->isFacultyTeacher())
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">Status</p>
                            <p class="flex-1 text-gray-800">{{ $user->status ?? '-' }}</p>
                        </li>
                        @endif
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">Designation</p>
                            <p class="flex-1 text-gray-800">{{ $user->designation ?? '-' }}</p>
                        </li>
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">College/Department</p>
                            <p class="flex-1 text-gray-800">{{ optional($user->college)->name ?? '-' }}</p>
                        </li>
                    </ul>
                </div>
            </x-tab-content>
        </x-tabbed-pane>
    </div>

    <div class="col-span-2">
        @include('_partials.user-profile.current-teaching-detail', [
            'currentTeachingDetails' => $user->teachingDetails
        ])
    </div>
</div>
@endsection

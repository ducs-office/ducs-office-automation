@extends('layouts.master')
@push('modals')
<x-modal name="edit-user-basic-info-modal" class="p-6 w-1/2"
    :open="$errors->update->hasAny(['phone', 'address'])">
    <h2 class="text-lg font-bold mb-3">{{ $user->name }}</h2>
    <h3 class="mb-6 font-bold">Basic Information</h3>
    @include('_partials.forms.edit-user-basic-info')
</x-modal>
<x-modal name="edit-user-work-modal" class="p-6 w-1/2"
    :open="$errors->update->hasAny(['status', 'designation', 'affiliation', 'college_id'])">
    <h2 class="text-lg font-bold mb-3">{{ $user->name }}</h2>
    <h3 class="mb-6 font-bold">Work Details</h3>
    @include('_partials.forms.edit-user-work')
</x-modal>
@endpush
@section('body')
<div class="m-4 grid gap-8 grid-cols-2 items-start">
    <div class="col-span-2 page-card p-6 overflow-visible">
        <div class="-mt-6 -mx-6 bg-magenta-800 h-48 rounded-t-md flex justify-end items-end p-4">
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
                <div class="flex items-center">
                    <h3 class="px-3 text-lg font-bold">
                        Basic Information
                    </h3>
                    @can('updateProfile', $user)
                    <x-modal.trigger modal="edit-user-basic-info-modal"  title="Edit"
                        class="p-1 ml-auto text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
                        <x-feather-icon name="edit" class="h-current mr-2"> Edit </x-feather-icon>
                    </x-modal.trigger>
                    @endcan
                </div>

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
                <div class="flex items-center">
                    <h3 class="px-3 text-lg font-bold">
                        Work Details
                    </h3>
                    @can('updateProfile', $user)
                    <x-modal.trigger modal="edit-user-work-modal"  title="Edit"
                        class="p-1 ml-auto text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
                        <x-feather-icon name="edit" class="h-current mr-2"> Edit </x-feather-icon>
                    </x-modal.trigger>
                    @endcan
                </div>
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
                        @if ($user->isExternal())
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">Affiliation</p>
                            <p class="flex-1 text-gray-800">{{ $user->affiliation ?? '-' }}</p>
                        </li>
                        @else
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">College/Department</p>
                            <p class="flex-1 text-gray-800">{{ optional($user->college)->name ?? '-' }}</p>
                        </li>
                        @endif
                    </ul>
                </div>
            </x-tab-content>
        </x-tabbed-pane>
    </div>
    @canany(['viewAny', 'create'], App\Models\TeachingDetail::class)
        <div class="col-span-2">
            @include('_partials.user-profile.current-teaching-detail', [
                'currentTeachingDetails' => $user->teachingDetails
            ])
        </div>
    @endcanany
</div>
@endsection

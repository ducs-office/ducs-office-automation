@extends('layouts.master')
@section('body')
<div class="m-4 grid gap-8 grid-cols-2 items-start">
    <div class="col-span-2 page-card p-6 overflow-visible">
        <div class="-mt-6 -mx-6 bg-magenta-800 h-48 rounded-t-md flex justify-end items-end p-4">
        </div>
        <div class="-mt-24 space-y-4 text-center mb-8" x-data="{editMode: 'false'}">
            <img src="{{ $user->avatar_url }}"
            class="flex items-center justify-center w-48 h-48 mx-auto object-cover border-4 border-white bg-white rounded-full shadow-md overflow-hidden"
            alt="{{ $user->name }}"
            x-show="editMode == 'false'">
            @can('updateProfile', $user)
            <div class="w-full flex justify-center ml-20">
                <button x-show="editMode == 'false'" x-on:click.prevent="editMode = 'true'"
                class="text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
                    <x-feather-icon name="edit" class="h-current -mt-8"> Edit </x-feather-icon>
                </button>
            </div>
            @endcan
            <form action="{{ route('profiles.update', $user) }}" method="POST" x-show="editMode == 'true'" enctype="multipart/form-data">
            @csrf_token @method('PATCH')
                <x-input.image id="avatar" name="avatar"
                imageSrc="{{ $user->avatar_url }}"
                class="cursor-pointer flex items-center justify-center w-48 h-48 mx-auto object-cover border-4 border-white bg-white rounded-full shadow-md overflow-hidden">
                <img x-bind:src="src" x-bind:alt="alt">
                </x-input.image>
                <div class="mt-2">
                    <button type="submit" class="btn btn-magenta w-20 inline-flex justify-center py-1 mx-1">Save</button>
                    <button class="btn btn-magenta w-20 inline-flex justify-center py-1  mx-1" x-on:click.prevent="editMode = 'false'">Cancel</button>
                </div>
            </form>
            @error('avatar', 'update')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
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
                @include('_partials.user-profile.basic-info')
            </x-tab-content>

            <x-tab-content tab="teaching" class="space-y-3 w-full max-w-2xl mx-auto">
                @include('_partials.user-profile.work-details')
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

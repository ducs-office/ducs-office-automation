@extends('layouts.scholar-profile', ['pageTitle' => 'Profile', 'scholar' => $scholar])
@section('body')
<div class="m-4 grid gap-8 grid-cols-2 items-start">
    <div class="col-span-2 page-card p-6 overflow-x-hidden">
        <div class="-mt-6 -mx-6 bg-magenta-800 h-48 rounded-t-md flex justify-end items-end p-4">
        </div>
        <div class="-mt-24 space-y-4 text-center mb-8" x-data="{editMode: 'false'}">
            <img src="{{ $scholar->avatar_url }}"
            class="flex items-center justify-center w-48 h-48 mx-auto object-cover border-4 border-white bg-white rounded-full shadow-md overflow-hidden"
            alt="{{ $scholar->name }}"
            x-show="editMode == 'false'">
            @can('updateProfile', [App\Models\Scholar::class, $scholar])
            <div class="w-full flex justify-center ml-20">
                <button x-show="editMode == 'false'" x-on:click.prevent="editMode = 'true'"
                class="text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
                    <x-feather-icon name="edit-3" class="h-current -mt-8"> Edit </x-feather-icon>
                </button>
            </div>
            @endcan
            <form action="{{ route('scholars.profile.update', $scholar) }}" method="POST" x-show="editMode == 'true'" enctype="multipart/form-data">
            @csrf_token @method('PATCH')
                <x-input.image id="avatar" name="avatar"
                imageSrc="{{ $scholar->avatar_url }}"
                class="group relative cursor-pointer flex items-center justify-center w-48 h-48 mx-auto object-cover border-4 border-white bg-white rounded-full shadow-md overflow-hidden">
                <div class="absolute group-hover:opacity-0 pointer-events-none inset-0 text-white bg-black-40 flex flex-col items-center justify-center z-10">
                    <x-feather-icon name="camera" class="h-4 w-4"></x-feather-icon>
                    <span>Click to Upload</span>
                </div>
                <img x-bind:src="src" x-bind:alt="alt" class="w-full h-full object-cover">
                </x-input.image>
                <p class="mt-2 text-xs text-gray-600"><b>Note:</b> Only PNG, JPG, JPEG files are accepted.</p>
                <div class="mt-2">
                    <button type="submit" class="btn btn-magenta w-20 inline-flex justify-center py-1 mx-1">Save</button>
                    <button class="btn btn-magenta w-20 inline-flex justify-center py-1  mx-1" x-on:click.prevent="editMode = 'false'">Cancel</button>
                </div>
            </form>
            @error('avatar', 'update')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
            <div>
                <h2 class="text-3xl">{{ $scholar->name }}</h2>
                <h3 class="text-xl text-gray-700">Scholar / {{ $scholar->research_area }}</h3>
            </div>
        </div>
        <x-tabbed-pane :current-tab="request()->query('tab', 'info')">
            <x-slot name="tabs">
                <div class="flex items-center justify-center space-x-3 border-b -mx-6 px-6">
                    <x-tab name="info">Basic Info</x-tab>
                    <x-tab name="admission">Admission Details</x-tab>
                    <x-tab name="education">Education Details</x-tab>
                    <x-tab name="committee">Research Committee</x-tab>
                </div>
            </x-slot>

            <x-tab-content tab="info" class="mt-5 w-full max-w-2xl mx-auto">
                @include('_partials.scholar-profile.basic-info')
            </x-tab-content>

            <x-tab-content tab="admission" class="mt-5 w-full max-w-2xl mx-auto">
                @include('_partials.scholar-profile.admission-details')
            </x-tab-content>

            <x-tab-content tab="education" class="mt-5 w-full max-w-2xl mx-auto">
                @include('_partials.scholar-profile.education-details')
            </x-tab-content>

            <x-tab-content tab="committee" class="mt-5 w-full max-w-2xl mx-auto">
                @include('_partials.scholar-profile.research-committee')
            </x-tab-content>
        </x-tabbed-pane>
    </div>
    <div class="col-span-2">
        @include('_partials.scholar-profile.pre-phd-courseworks', [
            'scholar' => $scholar
        ])
    </div>
    <div class="col-span-2">
        @include('_partials.scholar-profile.publications', [
            'scholar' => $scholar
        ])
    </div>
    <div class="col-span-2">
        @include('_partials.scholar-profile.presentations', [
            'scholar' => $scholar
        ])
    </div>

    <div class="col-span-2">
        @include('_partials.scholar-profile.leaves', [
            'scholar' => $scholar
        ])
    </div>

    <div class="col-span-2">
        @include('_partials.scholar-profile.advisory-meetings', [
            'scholar' => $scholar
        ])
    </div>

    <div class="col-span-2">
        @include('_partials.scholar-profile.progress-report', [
            'scholar' => $scholar
        ])
    </div>

    <div class="col-span-2">
        @include('_partials.scholar-profile.documents', [
            'scholar' => $scholar
        ])
    </div>

    <div class="col-span-2">
        @include('_partials.scholar-profile.pre-phd-seminar', [
            'scholar' => $scholar
        ])
    </div>

    <div class="col-span-2">
        @include('_partials.scholar-profile.title-approval', [
            'scholar' => $scholar
        ])
    </div>

    <div class="col-span-2">
        @include('_partials.scholar-profile.examiner', [
            'scholar' => $scholar
        ])
    </div>
</div>
@endsection

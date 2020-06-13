@extends('layouts.scholar-profile', ['pageTitle' => 'Profile', 'scholar' => $scholar])
@section('body')
<div class="page-card p-6 overflow-visible">
    <div class="-mt-6 -mx-6 bg-magenta-800 h-48 rounded-t-md flex justify-end items-end p-4">
    </div>
    <div class="-mt-24 space-y-4 text-center mb-8">
        <img src="{{ $scholar->avatar_url }}"
        class="flex items-center justify-center w-48 h-48 mx-auto object-cover border-4 border-white bg-white rounded-full shadow-md overflow-hidden"
        alt="{{ $scholar->name }}'s avatar">
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
@endsection

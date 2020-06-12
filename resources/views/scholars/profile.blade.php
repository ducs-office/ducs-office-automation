@extends('layouts.master', ['pageTitle' => 'Profile'])
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

        <x-tab-content tab="info" class="flex space-x-6">
            @include('_partials.research.scholar-profile.basic-info')
        </x-tab-content>

        <x-tab-content tab="admission" class="flex space-x-6">
            @include('_partials.research.scholar-profile.admission-details')
        </x-tab-content>

        <x-tab-content tab="education" class="flex space-x-6">
            @include('_partials.research.scholar-profile.education-details')
        </x-tab-content>

        <x-tab-content tab="committee" class="flex space-x-6">
            @include('_partials.research.scholar-profile.research-committee')
        </x-tab-content>

    </x-tabbed-pane>
</div>

    <div class="container mx-auto p-4 space-y-8">
        {{-- @include('_partials.research.scholar-profile.basic-info') --}}
        {{-- @include('_partials.research.scholar-profile.supervisors-card')
        @include('_partials.research.scholar-profile.cosupervisors-card')
        @include('_partials.research.scholar-profile.advisory-committee') --}}
        @include('_partials.research.scholar-profile.pre-phd-courseworks')
        @include('_partials.research.scholar-profile.publications')
        @include('_partials.research.scholar-profile.presentations')
        @include('_partials.research.scholar-profile.progress-reports')
        @include('_partials.research.scholar-profile.title-approval')
        @include('_partials.research.scholar-profile.examiner')
@endsection

@extends('layouts.scholar-profile', ['pageTitle' => 'Pre-PhD Courseworks', 'scholar' => $scholar])
@push('modals')
    <x-modal name="add-scholar-coursework-modal" class="p-6 w-1/2" 
        :open="!$errors->default->isEmpty()">
        <h2 class="text-lg font-bold mb-8"> Add Coursework - {{ $scholar->name }}</h2>
            @include('_partials.forms.add-scholar-coursework' ,[
                'courses' => $courses,
            ])
    </x-modal>
    <livewire:mark-scholar-coursework-completed-modal :error-bag="$errors->update" :scholar="$scholar" />
@endpush
@section('body')
<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Pre-PhD Coursework
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4">
            @foreach ($scholar->courseworks as $course)
                @include('_partials.list-items.pre-phd-coursework')
            @endforeach
        </ul>
        @can('create', [App\Models\Pivot\ScholarCoursework::class, $scholar])
            <x-modal.trigger  modal="add-scholar-coursework-modal"
                class="w-full btn btn-magenta rounded-lg py-3">
                + Add Coursework
            </x-modal.trigger>
        @endcan
    </div>
</div>
@endsection

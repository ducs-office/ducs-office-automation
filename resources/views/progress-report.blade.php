@extends('layouts.scholar-profile', ['pageTitle' => 'Progress Reports', 'scholar' => $scholar])
@push('modals')
@can('create', \App\Models\ProgressReport::class)
    <x-modal name="add-scholar-progress-reports-modal" class="p-6 w-1/2" 
        :open="!$errors->default->isEmpty()">
        <h2 class="text-lg font-bold mb-8"> Add Progress Report - {{ $scholar->name }}</h2>
        @include('_partials.forms.add-scholar-progress-reports', [
            'recommendations' => $recommendations,
        ])
    </x-modal>
@endcan
@endpush

@section('body')
<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
            @forelse ($scholar->progressReports as $progressReport)
            @include('_partials.list-items.progress-report')
            @empty
                <li class="px-4 py-3 text-center text-gray-700 font-bold">No Progress Reports yet.</li>
            @endforelse
        </ul>
        @can('create', \App\Models\ProgressReport::class)
        <x-modal.trigger class="mt-2 w-full btn btn-magenta rounded-lg py-3" 
           modal="add-scholar-progress-reports-modal">
            + Add Progress Reports
        </x-modal.trigger>
        @endcan
    </div>
</div>
@endsection
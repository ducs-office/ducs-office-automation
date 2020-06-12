{{--Other Documents--}}
@extends('layouts.master', ['pageTitle' => 'Documents', 'scholar' => $scholar])
@push('modals')
<x-modal name="add-documents-modal" class="p-6 min-w-1/2" :open="! $errors->default->isEmpty()">
    <h3 class="text-lg font-bold mb-4">Add Documents</h3>
    @include('_partials.forms.add-scholar-documents')
</x-modal>
@endpush
@section('body')
<div class="page-card p-6 overflow-visible">
    {{-- <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Documents
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div> --}}
    {{-- <div class="flex-1"> --}}
    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
        @forelse ($scholar->documents as $document)
        @include('_partials.list-items.scholar-document')
        @empty
        <li class="px-4 py-3 text-center text-gray-700 font-bold">No Documents</li>
        @endforelse
    </ul>
    @can('create', App\Models\ScholarDocument::class)
    <x-modal.trigger class="mt-2 w-full btn btn-magenta rounded-lg py-3" modal="add-documents-modal">
        + Add Documents
    </x-modal.trigger>
    @endcan
    {{-- </div> --}}
</div>
@endsection

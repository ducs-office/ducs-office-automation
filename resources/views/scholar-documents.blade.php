{{--Other Documents--}}
@extends('layouts.scholar-profile', ['pageTitle' => 'Documents', 'scholar' => $scholar])
@push('modals')
<x-modal name="add-documents-modal" class="p-6 min-w-1/2" :open="! $errors->default->isEmpty()">
    <h3 class="text-lg font-bold mb-4">Add Documents</h3>
    @include('_partials.forms.add-scholar-documents')
</x-modal>
@endpush
@section('body')
<div class="page-card p-6 overflow-visible">
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

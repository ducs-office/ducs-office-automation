@push('modals')
<x-modal name="add-documents-modal" class="p-6 min-w-1/2" :open="! $errors->addDocuments->isEmpty()">
    <h3 class="text-lg font-bold mb-4">Add Documents</h3>
    @include('_partials.forms.add-scholar-documents')
</x-modal>
@endpush
<div class="page-card p-6 overflow-visible">
    <div class="-mx-6 -mt-6 px-6 py-3 border-b mb-6 flex items-center">
        <div class="flex items-center space-x-2">
            <x-feather-icon name="paperclip" class="h-4 w-4"></x-feather-icon>
            <h2 class="text-lg font-bold">Other Documents</h2>
        </div>
        @can('create', App\Models\ScholarDocument::class)
        <x-modal.trigger class="ml-auto inline-flex items-center space-x-1 btn btn-magenta py-1 px-2"
            modal="add-documents-modal">
            <x-feather-icon name="plus" class="w-4 h-4"></x-feather-icon>
            <span>Add</span>
        </x-modal.trigger>
        @endcan
    </div>
    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
        @forelse ($scholar->documents as $document)
        @include('_partials.list-items.scholar-document')
        @empty
        <li class="px-4 py-3 text-center text-gray-700 font-bold">No Documents</li>
        @endforelse
    </ul>
</div>

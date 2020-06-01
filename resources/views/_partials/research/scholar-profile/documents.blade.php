{{--Other Documents--}}
<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Documents
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
            @forelse ($scholar->documents as $document)
                @can('view', $document)
                <li class="px-4 py-3">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="font-bold mr-2">{{ $document->date->format('d F Y') }}</p>
                            <p class="text-gray-700">{{ $document->description }}</p>
                        </div>
                        <a href="{{ route('scholars.documents.show', [$scholar, $document]) }}"
                            class="inline-flex items-center underline px-3 py-1 bg-gray-100 text-gray-900 rounded font-bold">
                        <x-feather-icon name="paperclip" class="h-4 mr-2"></x-feather-icon>
                            {{ $document->type }}
                        </a>
                        @can('delete', $document)
                        <form method="POST" action="{{ route('scholars.documents.destroy', [$scholar, $document]) }}"
                                onsubmit="return confirm('Do you really want to delete this document?');">
                                @csrf_token
                                @method('DELETE')
                                <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                                    <x-feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</x-feather-icon>
                                </button>
                            </form>
                        @endcan
                    </div>
                </li>
                @endcan
            @empty
                <li class="px-4 py-3 text-center text-gray-700 font-bold">No Documents</li>
            @endforelse
        </ul>
        @can('create', App\Models\ScholarDocument::class)
        <button class="mt-2 w-full btn btn-magenta rounded-lg py-3" @click="$modal.show('add-documents-modal')">
            + Add Documents
        </button>
        @endcan
        <x-modal name="add-documents-modal" height="auto">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Add Documents</h3>
                <form action="{{ route('scholars.documents.store', $scholar) }}" method="POST"
                    class="px-6" enctype="multipart/form-data">
                    @csrf_token
                    <div class="mb-2 items-center">
                        <div class="mb-2">
                            <label for="date" class="mb-1 w-full form-label">Date
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="date" id="date" class="w-full form-input" required>
                        </div>
                        <div class="mb-2">
                            <label for="document" class="mb-1 w-full form-label">Upload Document
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="file" name="document" id="">
                        </div>
                        <div class="mb-2">
                            <label for="type" class="mb-1 form-label w-full">
                                Type <span class="text-red-600">*</span>
                            </label>
                            <select name="type" id="type" class="form-select w-full">
                                @foreach ($documentTypes as $documentType)
                                    <option value="{{ $documentType }}">
                                        {{$documentType}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="description" class="mb-1 w-full form-label">Description
                            </label>
                            <textarea id="description" name="description" type="" class="w-full form-input" placeholder="Enter Description" required>
                            </textarea>
                        </div>
                    </div>
                    <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none">Add</button>
                </form>
            </div>
        </x-modal>
    </div>
</div>

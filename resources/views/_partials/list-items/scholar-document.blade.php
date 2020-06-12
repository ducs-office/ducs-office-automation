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

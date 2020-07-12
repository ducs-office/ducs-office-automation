<ul class="grid grid-cols-2 gap-6">
    @forelse($publication->coAuthors as $coAuthor)
    <li class="flex space-x-2 items-center px-3 py-1 border rounded">
        <div class="flex flex-1 items-center">
            <h3 class="text-md text-gray-800">{{ $coAuthor->name }}</h3>
            @if($coAuthor->noc_path)
                <div class="ml-auto">
                @can('view', $coAuthor)
                    <a href="{{ route('publications.co-authors.show', [
                        'publication' => $publication, 
                        'coAuthor' => $coAuthor
                    ]) }}" 
                    target="__blank" 
                    class="inline-flex space-x-1 items-center font-bold px-3 py-1 hover:bg-gray-100 rounded">
                        <x-feather-icon name="paperclip" class="h-4 w-4" stroke-width="2">NOC</x-feather-icon>
                        <span>NOC</span>
                    </a>
                    @endcan
                </div>
            @endif
        </div>
        <div class="ml-auto">
            @can('delete', [$coAuthor, $publication])
            <form action="{{ route('publications.co-authors.destroy', [
                'publication' => $publication, 
                'coAuthor' => $coAuthor
            ]) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete this co-author?');">
                @csrf_token
                @method('DELETE')
                <button type="submit" class="p-1 text-gray-700 font-bold hover:text-red-700 transition duration-300 transform hover:scale-110">
                    <x-feather-icon name="trash-2" class="h-5">Delete</x-feather-icon>
                </button>
            </form>
            @endcan
        </div>
    </li>
    @empty
    <li class="col-span-2 py-4 px-6 text-gray-600 font-bold"> Nothing to see here. </li>
    @endforelse
</ul>

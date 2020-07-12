@can('view', $presentation)
<li class="border-b last:border-b-0 py-3 flex items-baseline">
    <div class="ml-2">
        <div class="m-2">
            <h3 class="font-bold truncate">
                {{ $presentation->publication->paper_title }}
            </h3>
            <h5 class="text-sm text-gray-700 truncate">
                <span class="font-bold">{{ $presentation->scholar->name }}</span>
                <span class="">/</span>
                <span>{{ $presentation->publication->coAuthors->map->name->implode(', ') }}</span>
            </h5>
        </div>
        <div class="m-2">
            <h4 class="text-base font-medium mb-1">
                <span class="tracking-wide">{{ $presentation->event_name }} </span>
                <span class="mx-1 text-gray-700">&bullet;</span>
                <span class="text-gray-700">{{ $presentation->event_type }}</span>
            </h4>
            <div class="flex items-baseline">
                <x-feather-icon name="map-pin" class="h-3 text-blue-700"></x-feather-icon>
                <h4 class="text-base font-medium ml-1"> {{ $presentation->city}}, {{ $presentation->country }}</h4>
                <span>. </span>
                <h4 class="text-base font-medium ml-1">{{ $presentation->date->format('d F Y') }}</h4>
            </div>
        </div>
    </div>
    <div class="flex ml-auto items-center space-x-1 mr-3">
        @can('update', $presentation)
        <div class="mr-3">
            <a href="{{ route('scholars.presentations.edit', ['scholar' => $scholar, 'presentation' => $presentation]) }}"
                class="p-1 text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
                <x-feather-icon class="h-5" name="edit-3">Edit</x-feather-icon>
            </a>
        </div>
        @endcan
        @can('delete', $presentation)
        <form action="{{ route('scholars.presentations.destroy', ['scholar' => $scholar, 'presentation' => $presentation]) }}" method="POST"
            onsubmit="return confirm('Do you really want to delete this presentation?');">
            @csrf_token
            @method('DELETE')
            <button type="submit" class="p-1 text-gray-700 font-bold hover:text-red-600 transition duration-300 transform hover:scale-110">
                <x-feather-icon name="trash-2" class="h-5">Delete</x-feather-icon>
            </button>
        </form>
        @endcan
    </div>
</li>
@endcan

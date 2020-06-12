@can('view', $presentation)
<li class="border-b last:border-b-0 py-3 flex items-baseline">
    <div class="ml-2">
        <div class="flex m-2">
            <div class="w-30 flex">
                <x-feather-icon name="users" class="h-current text-blue-600"></x-feather-icon>
                <h4 class="ml-1 font-semibold"> Author: </h4>
            </div>
            <p class="ml-2"> {{ $presentation->scholar->name . ',' . implode(',', $presentation->publication->coAuthors->map->name->toArray()) }} </p>
        </div>
        <div class="m-2 flex">
            <div class="w-30 flex">
                <x-feather-icon name="book-open" class="h-current text-blue-600"></x-feather-icon>
                <h4 class="ml-1 font-semibold"> Title: </h4>
            </div>
            <p class="ml-2 italic"> {{ $presentation->publication->paper_title }} </p>
        </div>
        <div class="flex -m-1">
            <div class="w-3/5">
                <div class="flex m-2">
                    <h4 class="font-semibold"> Event Type: </h4>
                    <p class="ml-2"> {{ $presentation->event_type }} </p>
                </div>
                <div class="flex m-2">
                    <h4 class="font-semibold"> Event Name: </h4>
                    <p class="ml-2"> {{ $presentation->event_name }} </p>
                </div>
            </div>
            <div class="w-2/5">
                <div class="flex m-2">
                    <h4 class="font-semibold"> Date: </h4>
                    <p class="ml-2"> {{ $presentation->date->format('d F Y') }} </p>
                </div>
                <div class="flex m-2">
                    <h4 class="font-semibold"> Address: </h4>
                    <p class="ml-2"> {{ $presentation->city}}, {{ $presentation->country }} </p>
                </div>
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
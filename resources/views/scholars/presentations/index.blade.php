<div class="ml-2 mt-4 px-6">
    <ul class="border rounded-lg overflow-hidden mb-4">
    @foreach ($presentations as $presentation)
        <li class="border-b last:border-b-0 py-3">
            <div class="flex">
                <div class="ml-2 w-4/5">
                    <div class="flex m-2">
                        <div class="w-30 flex">
                            <x-feather-icon name="users" class="h-current text-blue-600"></x-feather-icon>
                            <h4 class="ml-1 font-semibold"> Author: </h4>
                        </div>
                        <p class="ml-2"> {{ implode(', ', $presentation->publication->authors) }} </p>
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
                <div class="ml-auto px-2 flex">
                    @can('update', $presentation)
                    <a href="{{ route('scholars.presentation.edit', $presentation) }}"
                        class="p-1 text-blue-600 hover:bg-gray-200 rounded mr-3 h-6" title="Edit">
                        <x-feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</x-feather-icon>
                    </a>
                    @endcan
                    @can('delete', $presentation)
                    <form method="POST" action="{{ route('scholars.presentation.destroy', $presentation->id) }}"
                        onsubmit="return confirm('Do you really want to delete this journal?');">
                        @csrf_token
                        @method('DELETE')
                        <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                            <x-feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</x-feather-icon>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </li>
    @endforeach
    @if (!count($presentations))
        <p class="text-gray-600 flex justify-center font-bold py-3 items-center">No presentations to show!</p>
    @endif
    </ul>
</div>

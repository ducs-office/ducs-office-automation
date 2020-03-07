<div class="mt-6 page-card">
    <div class="flex items-baseline px-4 mb-4">
        <div class="relative z-10 my-4 mt-8">
            <h5 class="relative z-20 pl-4 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                Publications
            </h5>
        </div>
        <a class="ml-auto btn btn-magenta is-sm shadow-inset" 
            target="_blank"
            href="{{ route('scholars.profile.publication.store')}}">
            New    
        </a>
    </div>
    <div class="mt-4">
        @foreach ($publications as $publication)
            <div class="mt-4 p-4 flex">
                <div class="items-center">
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Authors: </h4>
                        <div class="flex ml-2">
                            @foreach ($publication->authors as $author)
                                <p class="ml-1"> - {{ $author }}</p>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Title: </h4>
                            <p class="ml-2"> {{ $publication->title }}</p>
                    </div>
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Year: </h4>
                            <p class="ml-2"> {{ $publication->date}}</p>
                    </div>
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Venue: </h4>
                        <p class="ml-2"> {{ $publication->venue['city']}} , {{ $publication->venue['Country'] }}</p>
                    </div>
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Volume: </h4>
                        <p class="ml-2"> {{ $publication->volume }}</p>
                    </div>
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Number: </h4>
                        <p class="ml-2"> {{ $publication->number }}</p>
                    </div>
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Page Numbers: </h4>
                        <p class="ml-2"> {{ $publication->page_numbers['from'] }} - {{ $publication->page_numbers['to'] }}</p>
                    </div>
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Publisher: </h4>
                        <p class="ml-2"> {{ $publication->publisher }}</p>
                    </div>
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Indexed_in: </h4>
                        <div class="flex ml-2">
                            @foreach ($publication->indexed_in as $indexed)
                                <p class="ml-1"> - {{ $indexed }}</p>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Conference/Journal </h4>
                        <p class="ml-2"> {{ $publication->conference }}</p>
                    </div>
                </div>
                <div class="ml-auto flex">
                    <a href="{{ route('scholars.profile.publication.edit', $publication) }}" target="_blank"
                        class="p-1 text-gray-500 text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                        <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                    </a>
                    <form method="POST" action="{{ route('scholars.profile.publication.destroy', $publication->id) }}"
                        onsubmit="return confirm('Do you really want to delete this publication');">
                        @csrf_token
                        @method('DELETE')
                        <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                            <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
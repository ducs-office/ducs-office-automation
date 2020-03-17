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
    <div class="mt-4 px-6 items-center flex flex-wrap">
        @foreach ($publications as $publication)
            <div class="w-1/2 p-6">
                <div class="flex mb-3 items-center">
                    <span class="px-2 py-1 rounded text-xs uppercase text-white bg-blue-600 mr-2 font-bold">
                        {{ $publication->number}}
                    </span>
                    <h5 class="ml-4 text-gray-700 font-bold font-mono">Vol-{{ $publication->volume }}</h5>
                    <h5 class="text-gray-700 font-bold font-mono"> [{{ $publication->page_numbers['from'] }}-{{ $publication->page_numbers['to'] }}]</h5>
                    <h5 class="ml-4 text-gray-700 font-bold">{{ $publication->date }}</h5>
                    <div class="ml-4 flex items-center">
                        <feather-icon name="globe" class="h-current text-blue-600 mx-2">Venue</feather-icon>
                        <h5> {{ $publication->venue['city']}}, {{ $publication->venue['country'] }}</h5>
                    </div>
                    <div class="ml-auto flex">
                        <a href="{{ route('scholars.profile.publication.edit', $publication) }}" target="_blank"
                            class="p-1 text-gray-500 text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                            <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                        </a>
                        <form method="POST" action="{{ route('scholars.profile.publication.destroy', $publication->id) }}"
                            onsubmit="return confirm('Do you really want to delete this publication?');">
                            @csrf_token
                            @method('DELETE')
                            <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                                <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="mb-3 flex items-center">
                    <feather-icon name="users" class="h-current text-blue-600 mx-2">Authors</feather-icon>
                    @foreach ($publication->authors as $author)
                        <p class="mr-3"> {{ $author }}</p>
                    @endforeach
                </div>
                <div class="mb-3 flex items-center">
                    <feather-icon name="book-open" class="h-current text-blue-600 mx-2">Title</feather-icon>
                        <p class="ml-2"> {{ $publication->title }}</p>
                </div>
                <h4 class="mb-2 mr-5 text-gray-700"> Publisher: <span class="text-grey-700">{{ $publication->publisher }}</span></h4>
                <h4 class="mb-2 mr-4 text-gray-700"> Conference: <span class="text-grey-700">{{ $publication->conference }}</span></h4>
                <div class="mb-10 flex">
                    <h4 class="text-gray-700 mr-4"> Indexed In: </h4>
                    @foreach ($publication->indexed_in as $indexed)
                        <p class="mr-3 text-gray-700">{{ $indexed }}</p>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="w-1/2 p-6">
    <div class="flex mb-3 items-center">
        <span class="px-2 py-1 rounded text-xs uppercase text-white bg-blue-600 mr-2 font-bold">
            {{ $paper->number}}
        </span>
        <h5 class="ml-4 text-gray-700 font-bold font-mono">Vol-{{ $paper->volume }}</h5>
        <h5 class="text-gray-700 font-bold font-mono"> [{{ $paper->page_numbers['from'] }}-{{ $paper->page_numbers['to'] }}]</h5>
        <h5 class="ml-4 text-gray-700 font-bold">{{ $paper->date }}</h5>
        <div class="ml-4 flex items-center">
            <feather-icon name="globe" class="h-current text-blue-600 mx-2">Venue</feather-icon>
            <h5> {{ $paper->venue['city']}}, {{ $paper->venue['country'] }}</h5>
        </div>
        
    </div>
    <div class="mb-3 flex items-center">
        <feather-icon name="users" class="h-current text-blue-600 mx-2">Authors</feather-icon>
        @foreach ($paper->authors as $author)
            <p class="mr-3"> {{ $author }}</p>
        @endforeach
    </div>
    <div class="mb-3 flex items-center">
        <feather-icon name="book-open" class="h-current text-blue-600 mx-2">Title</feather-icon>
            <p class="ml-2"> {{ $paper->title }}</p>
    </div>
    <h4 class="mb-2 mr-5 text-gray-700"> Publisher: <span class="text-grey-700">{{ $paper->publisher }}</span></h4>
    <h4 class="mb-2 mr-4 text-gray-700"> Conference: <span class="text-grey-700">{{ $paper->conference }}</span></h4>
    <div class="mb-10 flex">
        <h4 class="text-gray-700 mr-4"> Indexed In: </h4>
        @foreach ($paper->indexed_in as $indexed)
            <p class="mr-3 text-gray-700">{{ $indexed }}</p>
        @endforeach
    </div>
</div>
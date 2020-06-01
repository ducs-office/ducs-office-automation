<ul class="border rounded-lg overflow-hidden mb-4">
    @foreach ($presentations as $presentation)
        <li class="border-b last:border-b-0 py-3">
            <div class="ml-2">
                <div class="flex m-2">
                    <div class="w-30 flex">
                        <x-feather-icon name="users" class="h-current text-blue-600"></x-feather-icon>
                        <h4 class="ml-1 font-semibold"> Author: </h4>
                    </div>
                    <p class="ml-2"> {{ auth()->user()->name . ',' . implode(',', $presentation->publication->coAuthors->map->name->toArray()) }} </p>
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
        </li>
    @endforeach
    @if (!count($presentations))
        <p class="text-gray-600 flex justify-center font-bold py-3 items-center">No presentations to show!</p>
    @endif
</ul>

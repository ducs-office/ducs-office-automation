<ul class="flex-1 border rounded-lg overflow-hidden mb-4">
    @forelse ($publications as $publication)
    <li class="border-b last:border-b-0 py-3">
        <div class="flex mt-2">
            <ul class="flex">
                @forelse($publication->coAuthors as $coAuthor)
                <li class="flex items-baseline">
                    <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 mx-2">
                        @can('view', $coAuthor)
                        <a href="{{ route('publications.co_authors.show', $coAuthor) }}" target="__blank" class="inline-flex items-center mr-1">
                            <x-feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">NOC</x-feather-icon>
                            <span>{{ $coAuthor->name }}</span>
                        </a>
                        @endcan
                    </div>
                </li>
                @empty
                    <p class="ml-2 p-2">No Co-authors associated with this publication.</p>
                @endforelse
            </ul>
            <div class="ml-auto p-2 flex">
                @can('update', $publication)
                <a href="{{ route('publications.edit', ['publication' => $publication]) }}"
                    class="p-1 text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                    <x-feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</x-feather-icon>
                </a>
                @endcan
                @can('delete', $publication)
                <form method="POST" action="{{ route('publications.destroy', $publication) }}"
                    onsubmit="return confirm('Do you really want to delete this publication?');">
                    @csrf_token
                    @method('DELETE')
                    <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                        <x-feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</x-feather-icon>
                    </button>
                </form>
                @endcan
            </div>
        </div>
        <div class="flex p-3 items-baseline">
            <p class="ml-2">
                {{auth()->user()->name . ',' . implode(',', $publication->coAuthors->map->name->toArray())}}
                <span class="italic"> {{ $publication->paper_title }} </span>
                
                @if($publication->isPublished())
                {{ $publication->date->format('F Y') }}.
                    {{ $publication->name }},
                    @if($publication->volume)
                        <span x-show="'{{$publication->type}}' == '{{App\Types\PublicationType::JOURNAL}}'"> Volume {{ $publication->volume }}, </span>
                        <span x-show="'{{$publication->type}}' == '{{App\Types\PublicationType::CONFERENCE}}'"> Edition {{ $publication->volume }}, </span>
                    @endif
                    @if($publication->number)
                        <span x-show="'{{$publication->type}}' == '{{App\Types\PublicationType::JOURNAL}}'">Number {{ $publication->number }},</span>
                    @endif
                    pp: {{ $publication->page_numbers[0] }}-{{ $publication->page_numbers[1] }}
                @endif
            </p>
            <div class="ml-auto flex">
                @can('view', $publication)
                <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 mx-2">
                    <a href="{{ route('publications.show', $publication) }}" target="__blank" class="inline-flex items-center mr-1">
                        <x-feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">Document</x-feather-icon>
                        @if($publication->isPublished())
                            <span>First Page</span>
                        @else
                            <span>Acceptance Letter</span>
                        @endif
                    </a>
                </div>
                @endcan
                @if($publication->paper_link)
                <a class="text-magenta-700 underline flex items-center my-2" href="{{ $publication->paper_link }}" target="__blank">
                    <span>View Paper</span>
                </a>
                @endif
            </div>
        </div>
        @if($publication->isPublished())
            <div class="w-full px-4">
                <details class="ml-2 mt-4 bg-gray-100 border rounded-t cursor-pointer outline-none">
                    <summary class="underline p-2 bg-gray-200 outline-none">Expand</summary>
                    <div class="flex m-2">
                        <div class="w-30 flex">
                            <x-feather-icon name="users" class="h-current text-blue-600"></x-feather-icon>
                            <h4 class="ml-1 font-semibold"> Author: </h4>
                        </div>
                        <p class="ml-2"> {{ auth()->user()->name }} </p>
                    </div>
                    <div class="flex m-2">
                        <div class="w-30 flex">
                            <x-feather-icon name="users" class="h-current text-blue-600"></x-feather-icon>
                            <h4 class="ml-1 font-semibold"> Co-Authors: </h4>
                        </div>
                        <p class="ml-2"> {{implode(',', $publication->coAuthors->map->name->toArray())}} </p>
                    </div>
                    <div class="m-2 flex">
                        <div class="w-30 flex">
                            <x-feather-icon name="book-open" class="h-current text-blue-600"></x-feather-icon>
                            <h4 class="ml-1 font-semibold"> Title: </h4>
                        </div>
                        <p class="ml-2 italic"> {{ $publication->paper_title }} </p>
                    </div>
                    <div class="flex -m-1">
                        <div class="w-3/5">
                            <div class="flex m-2">
                                <h4 class="font-semibold"> Name: </h4>
                                <p class="ml-2"> {{ $publication->name }} </p>
                            </div>
                            <div class="flex m-2" x-show="'{{$publication->type}}' == '{{App\Types\PublicationType::CONFERENCE}}'">
                                <h4 class="font-semibold"> Address: </h4>
                                <p class="ml-2"> {{ $publication->city}}, {{ $publication->country }} </p>
                            </div>
                            <div class="flex m-2" x-show="'{{$publication->type}}' == '{{App\Types\PublicationType::JOURNAL}}'">
                                <h4 class="font-semibold"> Publisher: </h4>
                                <p class="ml-2"> {{ $publication->publisher }} </p>
                            </div>
                            <div class="flex m-2">
                                <h4 class="font-semibold"> Indexed In: </h4>
                                <p class="ml-2"> {{ implode(', ', $publication->indexed_in) }} </p>
                            </div>
                            <div class="flex m-2">
                                <h4 class="font-semibold"> Pages: </h4>
                                <p class="ml-2"> {{ $publication->page_numbers[0] }}-{{ $publication->page_numbers[1] }} </p>
                            </div>
                        </div>
                        <div class="w-2/5">
                            <div class="flex m-2">
                                <h4 class="font-semibold"> Date: </h4>
                                <p class="ml-2"> {{ $publication->date->format('F Y') }} </p>
                            </div>
                            @if($publication->number)
                            <div class="flex m-2" x-show="'{{$publication->type}}' == '{{App\Types\PublicationType::JOURNAL}}'">
                                <h4 class="font-semibold"> Number: </h4>
                                <p class="ml-2"> {{ $publication->number }} </p>
                            </div>
                            @endif
                            @if($publication->volume)
                            <div class="flex m-2">
                                <h4 class="font-semibold" x-show="'{{$publication->type}}' == '{{App\Types\PublicationType::JOURNAL}}'"> Volume: </h4>
                                <h4 class="font-semibold" x-show="'{{$publication->type}}' == '{{App\Types\PublicationType::CONFERENCE}}'"> Edition: </h4>
                                <p class="ml-2"> {{ $publication->volume }} </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </details>
            </div>
        @endif
    </li>
    @empty
        <p class="px-4 py-3 text-center text-gray-700 font-bold">Nothing to show here!</p>
    @endforelse
</ul>

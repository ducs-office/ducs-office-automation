<li class="relative border-b last:border-b-0 p-3">
    <div class="flex justify-between items-start">
        <div class="flex-1 pr-4">
            <h3 class="font-bold truncate">
                @if($publication->paper_link)
                <a href="{{ $publication->paper_link }}" class="link">{{ $publication->paper_title }}</a>
                @else
                {{ $publication->paper_title }}
                @endif
            </h3>
            <h5 class="text-sm text-gray-700 truncate">
                <span class="font-bold">{{ $publication->author->name }}</span>
                <span class="">/</span>
                <span>{{ $publication->coAuthors->map->name->implode(', ') }}</span>
            </h5>
        </div>
        <div class="px-2 flex items-center space-x-3">
            <x-modal.trigger :livewire="['payload' => $publication->id]" modal="co-authors-modal" title="Add"
                class="p-1">
                <x-feather-icon name="users" class="h-4 w-4">Co-authors</x-feather-icon>
            </x-modal.trigger>
            @can('update', $publication)
            <a href="{{ $editRoute }}"
                class="p-1 text-blue-600 hover:bg-gray-200 rounded" title="Edit">
                <x-feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</x-feather-icon>
            </a>
            @endcan
            @can('delete', $publication)
            <form method="POST" action="{{ $deleteRoute }}"
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
    @if($publication->isPublished())
    <div class="flex justify-between items-end">
        <div class="mt-2 pr-4">
            <dl class="flex space-x-2">
                <dt class="font-bold capitalize">{{$publication->type}}</dt>
                <dd class="flex-1">
                    <span>{{ $publication->name }}</span>
                    @if($publication->volume)
                        @if($publication->isJournal()) 
                            <span>(Vol. {{ $publication->volume }})</span>
                        @else
                            <span>({{ $publication->volume }}<sup>th</sup> Edition)</span>
                        @endif
                    @endif
                    @if($publication->isJournal() && $publication->number)
                        <span>({{ $publication->number }})</span>
                    @endif
                </dd>
            </dl>
            <dl class="flex space-x-2">
                <dt class="font-bold">Pages:</dt>
                <dd class="italic">{{ implode('-', $publication->page_numbers) }}</dd>
            </dl>
        </div>
        @if($publication->document_path)
        <a href="{{ $publication->getUrl() }}" class="inline-flex items-center space-x-2 px-2 py-1 bg-gray-200 hover:bg-gray-400 rounded">
            <x-feather-icon name="paperclip" class="w-4 h-4"></x-feather-icon>
            <span>First Page</span>
        </a>
        @endif
    </div>
    <details class="mt-2">
        <summary class="link cursor-pointer">Other Details</summary>
        <div class="mt-2 pl-4 py-2 border-l-4 bg-gray-200 border-magenta-600">
            <div class="m-2 flex">
                <div class="w-30 flex items-center space-x-1">
                    <x-feather-icon name="book-open" class="h-current text-gray-700"></x-feather-icon>
                    <h4 class="ml-1 font-semibold"> Title: </h4>
                </div>
                <p class="ml-2 italic"> {{ $publication->paper_title }} </p>
            </div>
            <div class="flex m-2">
                <div class="w-30 flex items-center space-x-1">
                    <x-feather-icon name="user" class="h-current text-gray-700"></x-feather-icon>
                    <h4 class="ml-1 font-semibold"> Author: </h4>
                </div>
                <p class="ml-2"> {{ auth()->user()->name }} </p>
            </div>
            <div class="flex m-2">
                <div class="w-30 flex items-center space-x-1">
                    <x-feather-icon name="users" class="h-current text-gray-700"></x-feather-icon>
                    <h4 class="ml-1 font-semibold"> Co-Authors: </h4>
                </div>
                @if(count($publication->coAuthors))
                <p class="ml-2"> {{ $publication->coAuthors->map->name->implode(', ') }} </p>
                @else
                <p class="ml-2 text-gray-600"> No other Co-Authors..</p>
                @endif
            </div>
            <div class="flex -m-1">
                <div class="w-3/5">
                    <div class="flex m-2">
                        <h4 class="font-semibold capitalize"> {{ $publication->type }}: </h4>
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
        </div>
    </details>
    @elseif($publication->document_path)
    <div class="mt-2">
        <a href="{{ $publication->getUrl() }}" class="inline-flex items-center space-x-2 px-2 py-1 bg-gray-200 hover:bg-gray-400 rounded">
            <x-feather-icon name="paperclip" class="w-4 h-4"></x-feather-icon>
            <span>Acceptance Letter</span>
        </a>
    </div>
    @endif
</li>


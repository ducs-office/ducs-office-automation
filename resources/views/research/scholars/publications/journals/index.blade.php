
<div class="flex space-x-6">
    <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
        <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Journals
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <ul class="flex-1 border rounded-lg overflow-hidden mb-4">
        @forelse ($journals as $journal)
            <li class="border-b last:border-b-0 py-3">
                <p class="ml-2 p-2">
                    {{ implode(',', $journal->authors) }}.
                    {{ $journal->date->format('F Y') }}.
                    <span class="italic"> {{ $journal->paper_title }} </span>
                    {{ $journal->name }},
                    Volume {{ $journal->volume }},
                    Number {{ $journal->number }},
                    pp: {{ $journal->page_numbers[0] }}-{{ $journal->page_numbers[1] }}
                </p>
                <div class="w-full px-4">
                    <details class="ml-2 mt-4 bg-gray-100 border rounded-t cursor-pointer outline-none">
                        <summary class="underline p-2 bg-gray-200 outline-none">Expand</summary>
                        <div class="flex m-2">
                            <div class="w-30 flex">
                                <feather-icon name="users" class="h-current text-blue-600"></feather-icon>
                                <h4 class="ml-1 font-semibold"> Author: </h4>
                            </div>
                            <p class="ml-2"> {{ implode(', ', $journal->authors) }} </p>
                        </div>
                        <div class="m-2 flex">
                            <div class="w-30 flex">
                                <feather-icon name="book-open" class="h-current text-blue-600"></feather-icon>
                                <h4 class="ml-1 font-semibold"> Title: </h4>
                            </div>
                            <p class="ml-2 italic"> {{ $journal->paper_title }} </p>
                        </div>
                        <div class="flex -m-1">
                            <div class="w-3/5">
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Name: </h4>
                                    <p class="ml-2"> {{ $journal->name }} </p>
                                </div>
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Publisher: </h4>
                                    <p class="ml-2"> {{ $journal->publisher }} </p>
                                </div>
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Indexed In: </h4>
                                    <p class="ml-2"> {{ implode(', ', $journal->indexed_in) }} </p>
                                </div>
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Pages: </h4>
                                    <p class="ml-2"> {{ $journal->page_numbers[0] }}-{{ $journal->page_numbers[1] }} </p>
                                </div>
                            </div>
                            <div class="w-2/5">
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Issue Date: </h4>
                                    <p class="ml-2"> {{ $journal->date->format('F Y') }} </p>
                                </div>
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Number: </h4>
                                    <p class="ml-2"> {{ $journal->number }} </p>
                                </div>
                                <div class="flex m-2">
                                    <h4 class="font-semibold"> Volume: </h4>
                                    <p class="ml-2"> {{ $journal->volume }} </p>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>
            </li>
        @empty
            <p class="px-4 py-3 text-center text-gray-700 font-bold">No Journals</p>
        @endforelse
    </ul>
</div>

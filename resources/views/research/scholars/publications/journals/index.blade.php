<div class="mt-6 page-card mx-10">
    <div class="flex items-baseline px-6 mb-4">
        <div class="w-60 pr-4 relative z-10 -ml-8 my-2">
            <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Journals
            </h3>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>
    </div>
    <div class="mt-4 px-6">
        <ul class="border rounded-lg overflow-hidden mb-4">
            @foreach ($journals as $journal)
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
                        <details class="ml-2 mt-4 cursor-pointer">
                            <summary class="font-bold italic underline pb-4">Expand</summary>
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
                        @include('research.scholars.partials.presentations_show', [
                            'presentations' => $journal->presentations,
                        ])
                    </div>
                </li>
            @endforeach
        </ul class="border rounded-lg overflow-hidden mb-4">
    </div>
</div>
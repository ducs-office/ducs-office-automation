<div class="w-full px-4">
    <div class="flex m-2"> 
        <h4 class="font-semibold"> [{{ $index }}] </h4>
        <p class="ml-2">  
            {{ implode(',', $paper->authors) }}.
            {{ $paper->date->format('F Y') }}. 
            <span class="italic"> {{ $paper->title }} </span>
            {{ $paper->conference }}, 
            Volume {{ $paper->volume }}, 
            Number {{ $paper->number }}, 
            pp: {{ $paper->page_numbers['from'] }}-{{ $paper->page_numbers['to'] }}
        </p>
    </div> 
    <details class="ml-2">
        <summary class="font-bold italic underline pb-2">Details</summary>
        <div class="flex m-2">
            <div class="w-30 flex">
                <feather-icon name="users" class="h-current text-blue-600"></feather-icon>
                <h4 class="ml-1 font-semibold"> Author: </h4>
            </div>
            <p class="ml-2"> {{ implode(', ', $paper->authors) }} </p>
        </div>
        <div class="m-2 flex">
            <div class="w-30 flex">
                <feather-icon name="book-open" class="h-current text-blue-600"></feather-icon>
                <h4 class="ml-1 font-semibold"> Title: </h4>
            </div>
            <p class="ml-2 italic"> {{ $paper->title }} </p>
        </div>
        <div class="flex -m-1">
            <div class="w-3/5">
                <div class="flex m-2">
                    <h4 class="font-semibold"> Conference/Journal: </h4>
                    <p class="ml-2"> {{ $paper->conference }} </p>
                </div>
                <div class="flex m-2">
                    <h4 class="font-semibold"> Address: </h4> 
                    <p class="ml-2"> {{ $paper->venue['city']}}, {{ $paper->venue['country'] }} </p>
                </div>
                <div class="flex m-2">
                    <h4 class="font-semibold"> Publisher: </h4>
                    <p class="ml-2"> {{ $paper->publisher }} </p>
                </div>
                <div class="flex m-2">
                    <h4 class="font-semibold"> Indexed In: </h4>
                    <p class="ml-2"> {{ implode(', ', $paper->indexed_in) }} </p>
                </div>
            </div>
            <div class="w-2/5">
                <div class="flex m-2">
                    <h4 class="font-semibold"> Issue Date: </h4>
                    <p class="ml-2"> {{ $paper->date->format('d F Y') }} </p>
                </div>
                <div class="flex m-2">
                    <h4 class="font-semibold"> Number: </h4>
                    <p class="ml-2"> {{ $paper->number }} </p>
                </div>
                <div class="flex m-2">
                    <h4 class="font-semibold"> Volume: </h4>
                    <p class="ml-2"> {{ $paper->volume }} </p>
                </div>
                <div class="flex m-2">
                    <h4 class="font-semibold"> Pages: </h4>
                    <p class="ml-2"> {{ $paper->page_numbers['from'] }}-{{ $paper->page_numbers['to'] }} </p>
                </div>
            </div>
        </div>
    </details class="m-2">
</div>
   
<details class="ml-2 mt-4 cursor-pointer">
    <summary class="font-bold italic underline pb-4">Presentations</summary>
    <ol>
    @foreach ($presentations as $presentation)
        <li class="flex mt-2"> 
            <span class="font-bold px-1 ml-4 "> {{ $loop->iteration }}. </span>
            <div class="ml-2">
                <p> Presented at <span class="font-bold italic"> {{ $presentation->city }}, {{ $presentation->country }}</span> 
                    on <span class="font-bold italic"> {{ $presentation->date->format('d F Y') }} </span>.
                </p>
            </div>
        </li>
    @endforeach
    </ol>
    @if (!count($presentations))
        <p class="text-gray-600 flex justify-center font-bold">No presentations to show!</p>
    @endif
</details>
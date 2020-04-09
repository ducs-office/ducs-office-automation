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
            <div class="ml-auto px-2 flex">
                <a href="{{ route('scholars.profile.presentation.edit', $presentation) }}" 
                    class="p-1 text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                    <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                </a>
                <form method="POST" action="{{ route('scholars.profile.presentation.destroy', $presentation->id) }}"
                    onsubmit="return confirm('Do you really want to delete this journal?');">
                    @csrf_token
                    @method('DELETE')
                    <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                        <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                    </button>
                </form>
            </div>
        </li>
    @endforeach
    </ol>
    @if (!count($presentations))
        <p class="text-gray-600 flex justify-center font-bold">No presentations to show!</p>
    @endif
</details>
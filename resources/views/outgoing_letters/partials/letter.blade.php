<div class="px-6 py-4 hover:bg-gray-100 border-b">
    <div class="flex items-center mb-1">
        <h5 class="mr-12 text-gray-700 font-bold">{{ $letter->date->format('M d, Y') }}</h5>
        <div class="flex items-center text-gray-700">
            {{ $letter->sender->name }}
            <feather-icon name="arrow-up-right"
            stroke-width="3"
            class="h-current text-green-600 mx-2">Sent to</feather-icon>
            {{ $letter->recipient }}
        </div>
        <div class="ml-auto flex">
            <remark-modal name = "remark-modal"></remark-modal>
            <button 
                class="p-1 text-gray-500 hover:bg-gray-200 hover:text-green-600 rounded mr-3" 
                title="Add Remark"
                @click.prevent = "$modal.show('remark-modal', {
                    letter: {{ $letter->toJson() }},
                    remarks: {{ $letter->remarks->toJson()}}
                })">
                <feather-icon name="book-open" stroke-width="2.5" class="h-current">Add Remark</feather-icon>
            </button>
            <a href="/outgoing-letters/{{$letter->id}}/edit"
                class="p-1 text-gray-500 hover:bg-gray-200 hover:text-blue-600 rounded mr-3" title="Edit">
                <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
            </a>
            <form method="POST" action="/outgoing-letters/{{$letter->id}}">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                    <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                </button>
            </form>
        </div>
    </div>
    <h4 class="text-xl font-bold mb-1">{{ $letter->subject ?? 'Subject of Letter' }}</h4>
    @isset($letter->amount)
    <div class="flex items-end">
        <p class="w-2/3 text-black-50">{{ $letter->description }}</p>
        <div class="flex-1 px-4 text-xl font-bold text-right">
            &#x20B9;{{ substr(number_format($letter->amount, 2), 0, -2) }}
            <span class="text-sm">
                {{ substr(number_format($letter->amount, 2), -2, 2) }}
            </span>
        </div>
    </div>
    @else
        <p class="text-black-50">{{ $letter->description }}</p>
    @endisset
</div>

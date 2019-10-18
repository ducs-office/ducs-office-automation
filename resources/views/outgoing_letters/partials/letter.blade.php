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
    <div class = "mt-2 flex">
        <details >
            <summary class="inline-flex is-sm btn btn-magenta"> 
                Remarks
            </summary>
            <div class="py-2 hover:bg-gray-100 border-b justify-between overflow-y-auto">
                <div class="flex mt-2 mb-3">
                    <button class="btn btn-blue is-sm text-xs ml-auto" 
                        @click.prevent="$modal.show('create-letter-{{ $letter->id }}-remark')">
                        New
                    </button>
                </div>
                <remark-update-modal name="remark-update-modal">@csrf @method('patch')</remark-update-modal>
                <modal name="create-letter-{{ $letter->id }}-remark" height="auto">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2">Add Letter Remark</h3>
                        <h4 class="font-bold text-gray-600 mb-4">{{ $letter->subject }}</h4>
                        <form action="/remarks" method="POST">
                            @csrf <input type="hidden" name="letter_id" value="{{ $letter->id }}">
                            <div class="my-4">
                                <textarea name="description" placeholder="Give remarks here..." class="w-full form-input"></textarea>
                            </div>
                            <div>
                                <button class="btn btn-magenta is-sm">Create</button>
                            </div>
                        </form>
                    </div>
                </modal>
                @foreach($letter->remarks as $i => $remark)
                    <div class="flex mb-2">
                        <h4 class="font-bold text-sm text-gray-500 w-12">#{{ $i+1 }}</h4>
                        <h4 class="font-bold text-sm w-48">{{ $remark->updated_at->format('M d, Y h:i a') }}</h4>
                        <p class="text-gray-600 mr-2">{{ $remark->description }}</p>
                        
                        <div class="flex ml-auto items-baseline">
                            <button class="p-1 text-gray-500 hover:bg-gray-200 hover:text-blue-600 rounded mr-3" title="Edit"
                                @click.prevent="$modal.show('remark-update-modal',{
                                    remark: {{ $remark->toJson() }}
                                })">
                                <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                            </button>
                            <form action="/remarks/{{ $remark->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="p-1 hover:bg-gray-200 text-red-700 rounded">
                                    <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </details>
        <details class="ml-3">
            <summary class="inline-flex is-sm btn btn-magenta"> 
                Reminders
            </summary>
            <div class="py-2 hover:bg-gray-100 border-b justify-between overflow-y-auto">
                <div class="flex mt-2 mb-3">
                    <button class="btn btn-blue is-sm text-xs ml-auto" 
                        @click.prevent="$modal.show('create-letter-{{$letter->id}}-reminder')" >
                            New
                    </button> 
                </div>
                <reminder-update-modal name="reminder-update-modal">@csrf @method('patch')</reminder-update-modal>
                <modal name="create-letter-{{$letter->id}}-reminder" height="auto">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2">Upload Letter Reminder</h3>
                        <h4 class="font-bold text-gray-600 mb-4">{{ $letter->subject }}</h4>
                        <form action="/reminders" method="POST" enctype="multipart/form-data">
                            @csrf <input type="hidden" name="letter_id" value="{{ $letter->id }}">
                            <div class="my-4 flex">
                                {{-- <label for="pdf" class="btn btn-magenta is-sm">Upload pdf</label> --}}
                                <input type="file" name="pdf" accept="application/pdf" id="pdf"/>
                                {{-- <label for="scan" class="btn btn-magenta is-sm">Upload scanned</label> --}}
                                <input type="file" name="scan" accept="image/* , application/pdf" id="scan"/>
                            </div>
                            <div>
                                <button class="btn btn-magenta is-sm">Submit</button>
                            </div>
                        </form>
                    </div>
                </modal>
                @foreach($letter->reminders as $i => $reminder)
                    <div class="flex mb-2">
                        <h4 class="font-bold text-sm text-gray-500 w-12">{{ $reminder->id }}</h4>
                        <h4 class="font-bold text-sm w-48">{{ $reminder->updated_at->format('M d, Y h:i a') }}</h4>
                        
                        <form action="/attachments" method="GET">
                            <input name="file" value="{{$reminder->pdf}}" type="hidden">
                            @csrf
                            <button class="p-1 hover:bg-gray-200 text-blue-700 rounded">
                                 Pdf
                            </button>             
                        </form>
                        <form action="/attachments" method="GET">
                            <input name="file" value="{{$reminder->scan}}" type="hidden">
                            @csrf 
                            <button class="p-1 hover:bg-gray-200 text-blue-700">
                                Scan
                            </button>
                        </form>
                        <div class="flex ml-auto items-baseline">
                            <button class="p-1 text-gray-500 hover:bg-gray-200 hover:text-blue-600 rounded mr-3" title="Edit"
                                @click.prevent="$modal.show('reminder-update-modal',{
                                    reminder: {{ $reminder->toJson() }}
                                })"
                                {{-- onclick="alert('hello')" --}}
                                >
                                <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                            </button>
                            <form action="/reminders/{{ $reminder->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="p-1 hover:bg-gray-200 text-red-700 rounded">
                                    <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </details>
    </div>
</div>

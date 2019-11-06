<div class="page-card border-b mb-4 pt-6 pb-0 overflow-hidden">
        <div class="px-6">
            <div class="flex items-center mb-3">
                <h5 class="mr-12 text-gray-700 font-bold">{{$letter->serial_no}}</h5>
                <h5 class="mr-12 text-gray-700 font-bold">{{ $letter->date->format('M d, Y') }}</h5>
                <div class="flex items-center text-gray-700">
                    {{ $letter->sender}}
                    <feather-icon name="arrow-down-right"
                    stroke-width="3"
                    class="h-current text-green-600 mx-2">Recipient-</feather-icon>
                    {{ $letter->recipient->name }}
                </div>
                <div class="flex items-center text-gray-700">
                    <feather-icon name="corner-down-right"
                    stroke-width="3"
                    class="h-current text-blue-600 mx-2">Handover</feather-icon>
                    {{ $letter->handover->name}}
                </div>
                <div class="ml-auto flex">
                    <a href="/incoming-letters/{{$letter->id}}/edit"
                        class="p-1 text-gray-500 text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                        <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                    </a>
                    <form method="POST" action="/incoming-letters/{{$letter->id}}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                            <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                        </button>
                    </form>
                </div>
            </div>
            <h4 class="text-xl font-bold mb-3">{{ $letter->subject ?? 'Subject of Letter' }}</h4>
            <p class="text-black-50">{{ $letter->description }}</p>
            <div class="flex flex-wrap -mx-2 my-3">
                @foreach ($letter->attachments as $attachment)
                    <span class="p-2 rounded border hover:bg-gray-300 text-gray-600 m-2">
                        <a href="/attachments/{{ $attachment->id }}" target="__blank" class="inline-flex items-center mr-1">
                            <feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">View Attachment</feather-icon>
                            <span>{{ $attachment->original_name }}</span>
                        </a>
                    </span>
                @endforeach
            </div>
        </div>
        <v-tabbed-pane default-tab="remarks">
            <template v-slot:tabs="{ select, isActive }">
                <div class="flex px-6 border-b">
                    <button class="inline-flex items-center border border-b-0 rounded-t px-3 py-2 mx-1" 
                        style="margin-bottom: -1px;" role="tab" 
                        :class="{
                            'bg-gray-100': isActive('remarks'),
                            'bg-gray-300': !isActive('remarks'),
                        }"
                        @click="select('remarks')">
                        <feather-icon name="list" class="h-current mr-1">Remarks</feather-icon>
                        Remarks
                        <span class="ml-3 py-1 px-2 inline-flex items-center rounded-full bg-gray-500 text-xs text-black">{{ $letter->remarks->count() }}</span>
                    </button>
                </div>
            </template>
            <template v-slot:default="{ isActive }">
                <div v-show="isActive('remarks')">
                    @include('incoming_letters.partials.remarks')
                </div>
            </template>
        </v-tabbed-pane>
    </div>
    
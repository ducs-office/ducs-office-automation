<div class="page-card pt-6 pb-0 overflow-hidden">
        <div class="px-6">
            <div class="flex items-baseline mb-3">
                <div class="font-mono">
                    <h5 class="mr-12 text-gray-700 font-bold">{{$letter->serial_no}}</h5>
                    <h5 class="mr-12 text-gray-800">{{$letter->received_id}}</h5>
                </div>
                <h5 class="mr-12 text-gray-700 font-bold">{{ $letter->date->format('M d, Y') }}</h5>
                <div class="flex items-center text-gray-700">
                    {{ $letter->sender }}
                    <x-feather-icon name="arrow-down-right"
                    stroke-width="3"
                    class="h-current text-green-600 mx-2">Recipient</x-feather-icon>
                    {{ $letter->recipient->name }}
                    @if(count($letter->handovers))
                        <x-feather-icon name="corner-down-right"
                        stroke-width="3"
                        class="h-current text-blue-600 mx-2">Handovers</x-feather-icon>
                            {{ $letter->handovers->pluck('name')->implode(', ') }}
                    @endif
                </div>
                <div class="ml-auto flex">
                    @can('update', $letter)
                        <a href="{{ route('staff.incoming_letters.edit', $letter) }}"
                            class="p-1 text-gray-500 text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                            <x-feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</x-feather-icon>
                        </a>
                    @endcan
                    @can('delete', $letter)
                        <form method="POST" action="{{ route('staff.incoming_letters.destroy', $letter->id) }}"
                            onsubmit="return confirm('Do you really want to delete incoming letter?');">
                            @csrf_token
                            @method('DELETE')
                            <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                                <x-feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</x-feather-icon>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
            <h4 class="text-xl font-bold flex items-baseline mb-3">
                @isset($letter->priority)
                <span class="font-bold text-xl {{ $letter->priority->getContextCSS() }} mr-2"
                    title="{{ $letter->priority }}">
                    {{ str_repeat('!', $letter->priority->getDegree()) }}
                </span>
                @endisset
                <span class="flex-1">{{ $letter->subject ?? 'Subject of Letter' }}</span>
            </h4>
            <p class="text-black-50">{{ $letter->description }}</p>
            <div class="flex flex-wrap -mx-2 my-3">
                @foreach ($letter->attachments as $attachment)
                <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
                    <a href="{{ route('staff.attachments.show', $attachment) }}" target="__blank" class="inline-flex items-center mr-1">
                        <x-feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">View Attachment</x-feather-icon>
                        <span>{{ $attachment->original_name }}</span>
                    </a>
                    @can('delete', $attachment)
                    <button type="submit" form="remove-attachment" formaction="{{ route('staff.attachments.destroy', $attachment) }}"
                        class="p-1 rounded hover:bg-red-500 hover:text-white">
                        <x-feather-icon name="x" class="h-4" stroke-width="2">Delete Attachment</x-feather-icon>
                    </button>
                    @endcan
                </div>
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
                        <x-feather-icon name="list" class="h-current mr-1">Remarks</x-feather-icon>
                        Remarks
                        <span class="ml-3 py-1 px-2 inline-flex items-center rounded-full bg-gray-500 text-xs text-black">{{ $letter->remarks->count() }}</span>
                    </button>
                </div>
            </template>
            <template v-slot:default="{ isActive }">
                <div v-show="isActive('remarks')">
                    @include('staff.incoming_letters.remarks.index')
                </div>
            </template>
        </v-tabbed-pane>
    </div>

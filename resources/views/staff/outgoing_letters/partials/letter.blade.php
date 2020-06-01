<div class="page-card pt-6 pb-0 overflow-hidden">
    <div class="px-6">
        <div class="flex items-center mb-3">
            <span class="px-2 py-1 rounded text-xs uppercase text-white {{ $letter->type->contextCSS() }} mr-2 font-bold">
                {{ $letter->type }}
            </span>
            <h5 class="mr-12 text-gray-700 font-bold font-mono">{{$letter->serial_no}}</h5>
            <h5 class="mr-12 text-gray-700 font-bold">{{ $letter->date->format('M d, Y') }}</h5>
            <div class="flex items-center text-gray-700">
                {{ $letter->sender->name }}
                <x-feather-icon name="arrow-up-right"
                stroke-width="3"
                class="h-current text-green-600 mx-2">Sent to</x-feather-icon>
                {{ $letter->recipient }}
            </div>
            <div class="ml-auto flex">
                @can('update', $letter)
                <a href="{{ route('staff.outgoing_letters.edit', $letter) }}"
                    class="p-1 text-gray-500 text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                    <x-feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</x-feather-icon>
                </a>
                @endcan
                @can('delete', $letter)
                <form method="POST" action="{{ route('staff.outgoing_letters.destroy', $letter) }}"
                    onsubmit="return confirm('Do you really want to delete outgoing letter?');">
                    @csrf_token
                    @method('DELETE')
                    <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                        <x-feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</x-feather-icon>
                    </button>
                </form>
                @endcan
            </div>
        </div>
        <h4 class="text-xl font-bold mb-3">{{ $letter->subject ?? 'Subject of Letter' }}</h4>
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
                @can('viewAny', App\Models\Remark::class)
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
                @endcan
                @can('viewAny', App\Models\LetterReminder::class)
                <button class="inline-flex items-center border border-b-0 rounded-t px-3 py-2 mx-1"
                    style="margin-bottom: -1px;" role="tab"
                    :class="{
                        'bg-gray-100': isActive('reminders'),
                        'bg-gray-300': !isActive('reminders'),
                    }"
                    @click="select('reminders')">
                    <x-feather-icon name="bell" class="h-current mr-1">Reminders</x-feather-icon>
                    Reminders
                    <span class="ml-3 py-1 px-2 inline-flex items-center rounded-full bg-gray-500 text-xs text-black">{{ $letter->reminders->count() }}</span>
                </button>
                @endcan
            </div>
        </template>
        <template v-slot:default="{ isActive }">
            @can('viewAny', App\Models\Remark::class)
            <div v-show="isActive('remarks')">
                @include('staff.outgoing_letters.remarks.index')
            </div>
            @endcan
            @can('viewAny', App\Models\LetterReminder::class)
            <div v-show="isActive('reminders')">
                @include('staff.outgoing_letters.reminders.index')
            </div>
            @endcan
        </template>
    </v-tabbed-pane>
</div>


<div class="bg-gray-100">
    <reminder-update-modal name="reminder-update-modal">
        @csrf @method('patch')
    </reminder-update-modal>
    <form action="/reminders" method="POST" enctype="multipart/form-data" class="px-6 py-2 border-b">
        @csrf <input type="hidden" name="letter_id" value="{{ $letter->id }}">
        <div class="flex">
            <v-file-input id="scan"
                class="inline-flex items-center cursor-pointer form-input is-sm mr-4"
                placeholder="Upload a scanned copy"
                name="attachments[]"
                accept="image/*, application/pdf">
                <template v-slot="{ label }">
                    <feather-icon name="courseclip" class="h-current mr-2"></feather-icon>
                    <span v-text="label"></span>
                </template>
            </v-file-input>
            <v-file-input id="pdf"
                class="inline-flex items-center cursor-pointer form-input is-sm mr-4"
                placeholder="Upload a PDF copy"
                name="attachments[]"
                accept="application/pdf">
                <template v-slot="{ label }">
                    <feather-icon name="courseclip" class="h-current mr-2"></feather-icon>
                    <span v-text="label"></span>
                </template>
            </v-file-input>
            <button type="submit" class="btn btn-magenta is-sm">Add Reminder</button>
        </div>
    </form>
    @forelse($letter->reminders as $i => $reminder)
    <div class="flex py-3 hover:bg-gray-200 px-6">
        <h4 class="text-sm w-32 px-2">{{ $reminder->updated_at->format('M d, h:i a') }}</h4>
        <h4 class="px-2 text-sm text-gray-800 font-bold">{{ $reminder->serial_no }}</h4>
        <div class="px-2 flex-1 flex flex-wrap -mx-2 items-center">
            @foreach($reminder->attachments as $attachment)
            <a href="/attachments/{{ $attachment->id }}" target="_blank"
                class="p-1 text-xs text-gray-700 bg-gray-300 hover:bg-gray-400 rounded mx-2 inline-flex items-center">
                <feather-icon name="courseclip" stroke-width="2" class="h-current mr-1">{{ $attachment->original_name }}
                </feather-icon>
                {{ $attachment->original_name }}
            </a>
            <button type="submit"
                form="remove-attachment"
                formaction="/attachments/{{ $attachment->id}}"
                class="p-1 rounded hover:bg-red-500 hover:text-white">
                <feather-icon name="x" class="h-4" stroke-width="2">Delete Attachment</feather-icon>
            </button>
            @endforeach
        </div>
        <div class="px-2 flex items-baseline">
            <button class="p-1 text-gray-500 hover:bg-gray-200 text-blue-600 rounded mr-3" title="Edit"
                @click.prevent="$modal.show('reminder-update-modal',{
                                reminder: {{ $reminder->toJson() }}
                            })">
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
    @empty
    <div class="py-3 px-6">
        <p class="text-gray-600">No Reminders sent yet.</p>
    </div>
    @endforelse
    <form id="remove-attachment"
        method="POST"
        onsubmit="return confirm('Do you really want to delete attachment?');">
        @csrf @method('DELETE')
    </form>
</div>

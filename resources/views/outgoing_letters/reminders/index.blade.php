
<div class="bg-gray-100">
    @include('outgoing_letters.reminders.modals.edit', [
        'modalName' => 'edit-reminder-modal'
    ])
    @can('create', [\App\LetterReminder::class, $letter])
    <form action="{{ route('outgoing_letters.reminders.store', $letter) }}" method="POST" enctype="multipart/form-data" class="px-6 py-2 border-b">
        @csrf_token
        <div class="flex items-center">
            <div class="flex-1">
                <label for="scan" class="w-full form-label">Scanned Copy</label>
                <input id="scan"
                    class="w-full"
                    type="file"
                    name="attachments[]"
                    accept="image/*"/>
            </div>
            <div class="flex-1">
                <label for="pdf" class="w-full form-label">PDF Copy</label>
                <input id="pdf" class="w-full" type="file" name="attachments[]" accept="application/pdf" />
            </div>
            <button type="submit" class="btn btn-magenta is-sm">Add Reminder</button>
        </div>
    </form>
    @endcan
    @forelse($letter->reminders as $i => $reminder)
    <div class="flex items-center py-3 hover:bg-white px-6">
        <h4 class="text-sm w-32 px-2">{{ $reminder->updated_at->format('M d, h:i a') }}</h4>
        <h4 class="px-2 text-sm text-gray-800 font-bold">{{ $reminder->serial_no }}</h4>
        <div class="px-2 flex flex-wrap -mx-2">
            @foreach ($reminder->attachments as $attachment)
            <div class="inline-flex items-center px-2 py-1 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
                <a href="{{ route('attachments.show', $attachment) }}" target="__blank" class="inline-flex items-center mr-1">
                    <feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">View Attachment</feather-icon>
                    <span>{{ $attachment->original_name }}</span>
                </a>
                @can('delete', $attachment)
                <button type="submit" form="remove-attachment" formaction="{{ route('attachments.destroy', $attachment) }}"
                    class="p-1 rounded hover:bg-red-500 hover:text-white">
                    <feather-icon name="x" class="h-4" stroke-width="2">Delete Attachment</feather-icon>
                </button>
                @endcan
            </div>
            @endforeach
        </div>
        <div class="px-2 flex items-baseline">
            @can('update', $reminder)
            <button class="p-1 text-gray-500 hover:bg-gray-200 text-blue-600 rounded mr-3" title="Edit"
                @click.prevent="$modal.show('edit-reminder-modal',{
                                reminder: {{ $reminder->toJson() }}
                            })">
                <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
            </button>
            @endcan
            @can('delete', $reminder)
            <form action="{{ route('reminders.destroy', $reminder) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete this reminder?');">
                @csrf_token
                @method('DELETE')
                <button class="p-1 hover:bg-gray-200 text-red-700 rounded">
                    <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                </button>
            </form>
            @endcan
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
        @csrf_token @method('DELETE')
    </form>
</div>

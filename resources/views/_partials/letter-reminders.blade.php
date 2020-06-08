<div>
    <livewire:edit-reminder-modal />
    @can('create', [\App\Models\LetterReminder::class, $letter])
    <form action="{{ route('staff.outgoing_letters.reminders.store', $letter) }}" method="POST" enctype="multipart/form-data" class="px-6 py-2 bg-gray-100 border-b">
        @csrf_token
        <div class="flex items-start space-x-2">
            <div class="flex-1">
                <x-input.file id="pdf" :multiple="true"
                    name="attachments[]" accept="application/pdf, image/*"
                    class="w-full form-input overflow-hidden"
                    placeholder="Upload maximum 2 PDF or Scanned Image file(s)">
                </x-input.file>
                @if($errors->has('attachments'))
                <p class="mt-1 text-red-600">{{ $errors->first('attachments') }}</p>
                @endif
            </div>
            <button type="submit" class="btn btn-magenta">Add Reminder</button>
        </div>
    </form>
    @endcan
    @forelse($letter->reminders as $i => $reminder)
    <div class="flex items-center py-3 hover:bg-gray-100 px-6">
        <h4 class="text-sm w-32 px-2">{{ $reminder->updated_at->format('M d, h:i a') }}</h4>
        <h4 class="px-2 text-sm text-gray-800 font-bold">{{ $reminder->serial_no }}</h4>
        <div class="px-2 flex flex-wrap -mx-2">
            @foreach ($reminder->attachments as $attachment)
            <div class="inline-flex items-center px-2 py-1 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
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
        <div class="ml-auto px-2 flex items-baseline">
            @can('update', $reminder)
            <x-modal.trigger modal="edit-reminder-modal" :livewire="['payload' => $reminder->id]" class="p-1 text-gray-500 hover:bg-gray-200 text-blue-600 rounded mr-3" title="Edit">
                <x-feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</x-feather-icon>
            </x-modal.trigger>
            @endcan
            @can('delete', $reminder)
            <form action="{{ route('staff.reminders.destroy', $reminder) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete this reminder?');">
                @csrf_token
                @method('DELETE')
                <button class="p-1 hover:bg-gray-200 text-red-700 rounded">
                    <x-feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</x-feather-icon>
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

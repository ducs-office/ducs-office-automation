@extends('layouts.master')
@section('body')
    <div class="page-card max-w-xl my-4 mx-auto">
        <div class="page-header flex items-baseline">
            <h2 class="mr-4">Update Incoming Letter</h2>
            <span class="ml-auto px-2 py-1 rounded text-sm uppercase text-white font-bold font-mono bg-gray-800">
                {{ $letter->serial_no }}
            </span>
        </div>
        <form action="{{ route('staff.incoming_letters.update', $letter) }}"
            method="POST"
            class="px-6"
            enctype="multipart/form-data">
            @csrf_token
            @method('PATCH')
            <div class="mb-2">
                <label for="date" class="w-full form-label mb-1">
                    Date of Receipt <span class="text-red-600">*</span>
                </label>
                <input id="date"
                    type="date" name="date"
                    value="{{ old('date', $letter->date->format('Y-m-d')) }}"
                    class="w-full form-input{{ $errors->has('date') ? ' border-red-600' : ''}}"
                    requried>
                @if($errors->has('date'))
                    <p class="mt-1 text-red-600">{{ $errors->first('date') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="received_id" class="w-full form-label mb-1">
                    Received Letter Id <span class="text-red-600">*</span>
                </label>
                <input id="received_id"
                    type="text" name="received_id"
                    value="{{ old('received_id', $letter->received_id) }}"
                    class="w-full form-input{{ $errors->has('received_id') ? ' border-red-600' : ''}}"
                    placeholder="ID / Serial Number on the received Letter"
                    required>
                @if($errors->has('received_id'))
                    <p class="mt-1 text-red-600">{{ $errors->first('received_id') }}</p>
                @endif
            </div>
            <div class="flex space-x-2 mb-2">
                <div class="flex-1">
                    <label for="sender" class="w-full form-label mb-1">
                        Sender <span class="text-red-600">*</span>
                    </label>
                    <input id="sender"
                        type="text" name="sender"
                        value="{{ old('sender', $letter->sender) }}"
                        class="w-full form-input{{ $errors->has('sender') ? ' border-red-600' : ''}}"
                        placeholder="Sender (Received from)"
                        requried>
                    @if($errors->has('sender'))
                        <p class="mt-1 text-red-600">{{ $errors->first('sender') }}</p>
                    @endif
                </div>
                <div class="flex-1 mr-1">
                    <label for="receiver" class="w-full form-label mb-1">
                        Recipient <span class="text-red-600">*</span>
                    </label>
                    <livewire:typeahead-users
                        name="recipient_id"
                        limit="15"
                        value="{{ old('recipient_id', $letter->recipient_id) }}"
                        placeholder="Receiver (Received by)"
                        search-placeholder="Search receiver from users..." />
                    @if($errors->has('recipient_id'))
                        <p class="mt-1 text-red-600">{{ $errors->first('recipient_id') }}</p>
                    @endif
                </div>
            </div>
            <div class="mb-2">
                <label for="handovers-update" class="w-full form-label mb-1">Handed Over To</label>
                <livewire:typeahead-users
                    id="handovers-update"
                    name="handovers[]"
                    limit="15"
                    :multiple="true"
                    :value="old('handovers', $letter->handovers->pluck('id')->all())"
                    placeholder="Handed Over To"
                    search-placeholder="Search from users..."/>
                @if($errors->has('handovers'))
                    <p class="mt-1 text-red-600">{{ $errors->first('handovers') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="subject" class="w-full form-label mb-1">
                    Subject  <span class="text-red-600">*</span>
                </label>
                <input id="subject"
                    type="text" name="subject"
                    value="{{ old('subject', $letter->subject) }}"
                    class="w-full form-input{{ $errors->has('description') ? ' border-red-600' : ''}}"
                    placeholder="Subject of the letter"
                    required>
                @if($errors->has('subject'))
                    <p class="mt-1 text-red-600">{{ $errors->first('subject') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="priority" class="w-full form-label mb-1">Letter Priority</label>
                @php($oldPriority = old('priority', (string)$letter->priority))
                <select class="w-full form-select" name="priority">
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}"
                            {{ $priority === $oldPriority ? 'selected' : ''}}>
                            {{ $priority }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('priority'))
                    <p class="mt-1 text-red-600">{{ $errors->first('priority') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="description" class="w-full form-label mb-1">Description</label>
                <textarea name="description" id="description"
                placeholder="What is this letter about?"
                class="w-full form-input" rows="3">{{ old('description') ?? $letter->description }}</textarea>
                @if($errors->has('description'))
                    <p class="mt-1 text-red-600">{{ $errors->first('description') }}</p>
                @endif
            </div>
            <div class="mb-2">
                @if($letter->attachments->count())
                    <label for="attachments" class="w-full form-label mb-1">Add Attachments</label>
                    <div class="flex flex-wrap -mx-2 mb-2">
                        @foreach ($letter->attachments as $attachment)
                        <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
                            <a href="{{ route('staff.attachments.show', $attachment) }}" target="__blank" class="inline-flex items-center mr-1">
                                <x-feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">View Attachment</x-feather-icon>
                                <span>{{ $attachment->original_name }}</span>
                            </a>
                            @can('delete', $attachment)
                                <button type="submit"
                                    form="remove-attachment"
                                    formaction="{{ route('staff.attachments.destroy', $attachment) }}"
                                    class="p-1 rounded hover:bg-red-500 hover:text-white">
                                    <x-feather-icon name="x" class="h-4" stroke-width="2">Delete Attachment</x-feather-icon>
                                </button>
                            @endcan
                        </div>
                        @endforeach
                    </div>
                @else
                    <label for="attachments" class="w-full form-label mb-1">
                        Upload Attachments <span class="text-red-600">*</span>
                    </label>
                @endif
                <x-input.file id="files" name="attachments[]" accept="application/pdf,image/*"
                    class="w-full form-input overflow-hidden"
                    placeholder="Choose multiple Image/PDF files"
                    :multiple="true">
                </x-input.file>
                @if($errors->has('file'))
                    <p class="mt-1 text-red-600">{{ $errors->first('file') }}</p>
                @endif
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Update</button>
            </div>
        </form>
        <form id="remove-attachment"
            method="POST"
            onsubmit="return confirm('Do you really want to delete attachment?');">
            @csrf_token @method('DELETE')
        </form>
    </div>
@endsection

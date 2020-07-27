@extends('layouts.master')
@section('body')
    <div class="page-card max-w-xl my-4 mx-auto">
        <h2 class="page-header">New Incoming Letter</h2>
        <form action="{{ route('staff.incoming_letters.store') }}"
            method="POST"
            class="px-6"
            enctype="multipart/form-data">
            @csrf_token
            <div class="mb-2">
                <label for="date" class="w-full form-label mb-1">
                    Date of Receipt <span class="text-red-600">*</span>
                </label>
                <input id="date"
                    type="date" name="date"
                    class="w-full form-input{{ $errors->has('date') ? ' border-red-600' : ''}}"
                    value="{{ old('date') }}" required>
                @if($errors->has('date'))
                    <p class="mt-1 text-red-600">{{ $errors->first('date') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="received_id" class="w-full form-label mb-1">
                    Received Letter ID <span class="text-red-600">*</span>
                </label>
                <input id="received_id"
                    type="text" name="received_id"
                    value="{{ old('received_id') }}"
                    class="w-full form-input{{ $errors->has('received_id') ? ' border-red-600' : ''}}"
                    placeholder="ID/Serial Number on the received Letter"
                    required>
                @if($errors->has('received_id'))
                    <p class="mt-1 text-red-600">{{ $errors->first('received_id') }}</p>
                @endif
            </div>
            <div class="flex mb-2 space-x-2">
                <div class="flex-1">
                    <label for="sender" class="w-full form-label mb-1">
                        Sender <span class="text-red-600">*</span>
                    </label>
                    <input id="sender"
                    type="text" name="sender"
                    value="{{ old('sender') }}"
                    class="w-full form-input{{ $errors->has('sender') ? ' border-red-600' : ''}}"
                    placeholder="Sender (Received from)"
                    required>
                    @if($errors->has('sender'))
                        <p class="mt-1 text-red-600">{{ $errors->first('sender') }}</p>
                    @endif
                </div>
                <div class="flex-1">
                    <label for="receiver" class="w-full form-label mb-1">
                        Recipient <span class="text-red-600">*</span>
                    </label>
                    <livewire:typeahead-users
                        name="recipient_id"
                        limit="15"
                        value="{{ old('recipient_id') }}"
                        placeholder="Receiver (Received by)"
                        search-placeholder="search receiver from users..." />
                    @if($errors->has('recipient_id'))
                        <p class="mt-1 text-red-600">{{ $errors->first('recipient_id') }}</p>
                    @endif
                </div>
            </div>
            <div class="mb-2">
                <label for="handovers[]" class="w-full form-label mb-1">Handed Over To</label>
                <livewire:typeahead-users
                    name="handovers[]"
                    limit="15"
                    :multiple="true"
                    :value="old('handovers', [])"
                    placeholder="Handed Over To"
                    search-placeholder="Search from users..." />
                @if($errors->has('handovers'))
                    <p class="mt-1 text-red-600">{{ $errors->first('handovers') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="subject" class="w-full form-label mb-1">
                    Subject <span class="text-red-600">*</span>
                </label>
                <input id="subject"
                    type="text"
                    name="subject"
                    class="w-full form-input{{ $errors->has('subject') ? ' border-red-600' : ''}}"
                    value="{{ old('subject') }}"
                    placeholder="Subject of the letter"
                    required>
                @if($errors->has('subject'))
                    <p class="mt-1 text-red-600">{{ $errors->first('subject') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="priority" class="w-full form-label mb-1">Letter Priority</label>
                <select id="priority"
                    name="priority"
                    class="w-full form-input{{ $errors->has('priority') ? ' border-red-600' : ''}}">
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}"
                            {{ old('priority') === $priority ? 'selected' : ''}}>
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
                <textarea id="description"
                    name="description"
                    placeholder="What is this letter about?"
                    class="w-full form-input{{ $errors->has('description') ? ' border-red-600' : ''}}"
                    rows="3">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <p class="mt-1 text-red-600">{{ $errors->first('description') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="files" class="w-full form-label mb-1">
                    Upload Letter <span class="text-red-600">*</span>
                </label>
                <x-input.file id="files" name="attachments[]" accept="application/pdf,image/*" class="w-full form-input overflow-hidden"
                    placeholder="Choose multiple Image/PDF files" :multiple="true" required>
                </x-input.file>
                @if($errors->has('file.*'))
                    <p class="mt-1 text-red-600">{{ $errors->first('file') }}</p>
                @endif
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
@endsection

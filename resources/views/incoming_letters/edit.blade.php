@extends('layouts.master')
@section('body')
    <div class="page-card max-w-lg mt-4 mx-auto">
        <h1 class="page-header px-6">Update Incoming Letter</h1>
        <form action="/incoming-letters/{{ $incoming_letter->id }}" method="POST" class="px-6" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="mb-2">
                <label for="date" class="w-full form-label mb-1">Received Date</label>
                <input type="text" name="date" value="{{ old('date') ?? $incoming_letter->date->format('Y-m-d') }}" class="w-full form-input" placeholder="YYYY-MM-DD" onfocus="this.type='date'" onblur="this.type='text'">
                @if($errors->has('date'))
                    <p class="text-red-500">{{ $errors->first('date') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="received_id" class="w-full form-label mb-1">Letter Id</label>
                <input type="text" name="received_id" value="{{ old('received_id') ?? $incoming_letter->received_id }}" class="w-full form-input" placeholder="Id of the Letter">
                @if($errors->has('received_id'))
                    <p class="text-red-500">{{ $errors->first('received_id') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="sender" class="w-full form-label mb-1">Sender</label>
                <input type="text" name="sender" value="{{ old('sender') ?? $incoming_letter->sender }}" class="w-full form-input" placeholder="Sender (Received from)">
                @if($errors->has('sender'))
                    <p class="text-red-500">{{ $errors->first('sender') }}</p>
                @endif
            </div> 
            <div class="flex -mx-2 mb-2">
                <div class="mx-2">
                    <label for="receiver" class="w-full form-label mb-1">Receiver</label>
                    <vue-typeahead
                        name="recipient_id"
                        source="/api/users"
                        find-source="/api/users/{value}"
                        limit="5"
                        value="{{ old('recipient_id') ?? $incoming_letter->recipient_id }}"
                        placeholder="Receiver (Received by)">
                    </vue-typeahead>
                    @if($errors->has('recipient_id'))
                        <p class="text-red-500">{{ $errors->first('recipient_id') }}</p>
                    @endif
                </div> 
                <div class="mx-2">
                    <label for="handover_id" class="w-full form-label mb-1">Handover To</label>
                    <vue-typeahead
                        name="handover_id"
                        source="/api/users"
                        find-source="/api/users/{value}"
                        limit="5"
                        value="{{ old('handover_id') ?? $incoming_letter->handover_id }}"
                        placeholder="Handover To">
                    </vue-typeahead>
                    @if($errors->has('handover_id'))
                        <p class="text-red-500">{{ $errors->first('handover_id') }}</p>
                    @endif
                </div> 
            </div>
            <div class="mb-2">
                <label for="subject" class="w-full form-label mb-1">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') ?? $incoming_letter->subject }}" class="w-full form-input" placeholder="Subject of the letter">
                @if($errors->has('subject'))
                    <p class="text-red-500">{{ $errors->first('subject') }}</p>
                @endif
            </div> 
            <div class="mb 2">
                <label for="priority" class="w-full form-label mb-1">Letter Priority</label>
                @php($select = old('priority') ?? $incoming_letter->priority)
                <select class="w-full form-input" name="priority">
                    <option value="">None</option>
                    <option value="1" {{ $select == '1' ? 'selected' : ''}} >High</option>
                    <option value="2" {{ $select == '2' ? 'selected' : ''}}>Medium</option>
                    <option value="3" {{ $select == '3' ? 'selected' : ''}}>Low</option>
                </select>
                @if($errors->has('priority'))
                    <p class="text-red-500">{{ $errors->first('priority') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="description" class="w-full form-label mb-1">Description</label>
                <textarea name="description" id="description" 
                placeholder="What this letter is about?"
                class="w-full form-input" rows="3">{{ old('description') ?? $incoming_letter->description }}</textarea>
                @if($errors->has('description'))
                    <p class="text-red-500">{{ $errors->first('description') }}</p>
                @endif
            </div>
            <h5>Related Attachments</h5>
            <div class="flex flex-wrap -mx-2 mt-2">
                @foreach ($incoming_letter->attachments as $attachment)
                <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
                    <a href="/attachments/{{ $attachment->id }}" target="__blank" class="inline-flex items-center mr-1">
                        <feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">View Attachment</feather-icon>
                        <span>{{ $attachment->original_name }}</span>
                    </a>
                    <button type="submit" 
                        form="remove-attachment"
                        formaction="/attachments/{{ $attachment->id }}"
                        class="p-1 rounded hover:bg-red-500 hover:text-white">
                        <feather-icon name="x" class="h-4" stroke-width="2">Delete Attachment</feather-icon>
                    </button>        
                </div>
                @endforeach
            </div>
            <div class="mb-2">
                <label for="file" class="w-full form-label mb-1">Upload Letter</label>
                <input type="file" name="attachments[]" accept="image/*, application/pdf" class="w-full">
                @if($errors->has('file'))
                    <p class="text-red-500">{{ $errors->first('file') }}</p>
                @endif
            </div>
            <div class="mb-3">
                <button type="submit" class="w-full btn btn-magenta">Upload</button>
            </div>
        </form>
        <form id="remove-attachment" 
            method="POST"
            onsubmit="return confirm('Do you really want to delete attachment?');">
            @csrf @method('DELETE')
        </form>
    </div>
@endsection
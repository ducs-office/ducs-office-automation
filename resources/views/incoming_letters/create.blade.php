@extends('layouts.master')
@section('body')
    <div class="page-card max-w-lg mt-4 mx-auto">
        <h1 class="page-header px-6">New Incoming Letter</h1>
        <form action="{{ route('incoming_letters.store') }}" method="POST" class="px-6" enctype="multipart/form-data">
            @csrf_token
            <div class="mb-2">
                <label for="date" class="w-full form-label mb-1">Received Date</label>
                <input type="text" name="date" value="{{ old('date') }}" class="w-full form-input" placeholder="YYYY-MM-DD" onfocus="this.type='date'" onblur="this.type='text'">
                @if($errors->has('date'))
                    <p class="text-red-500">{{ $errors->first('date') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="received_id" class="w-full form-label mb-1">Letter Id</label>
                <input type="text" name="received_id" value="{{ old('received_id') }}" class="w-full form-input" placeholder="Id of the Letter">
                @if($errors->has('received_id'))
                    <p class="text-red-500">{{ $errors->first('received_id') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="sender" class="w-full form-label mb-1">Sender</label>
                <input type="text" name="sender" value="{{ old('sender') }}" class="w-full form-input" placeholder="Sender (Received from)">
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
                        value="{{ old('recipient_id') }}"
                        placeholder="Receiver (Received by)">
                    </vue-typeahead>
                    @if($errors->has('recipient_id'))
                        <p class="text-red-500">{{ $errors->first('recipient_id') }}</p>
                    @endif
                </div>
                <div class="mx-2">
                    <label for="handovers[]" class="w-full form-label mb-1">Handover To</label>
                    <v-multi-typeahead
                        name="handovers[]"
                        source="/api/users"
                        find-source="/api/users/{value}"
                        limit="5"
                        value="{{ old('handover_id') }}"
                        placeholder="Handover To">
                    </v-multi-typeahead>
                    @if($errors->has('handovers'))
                        <p class="text-red-500">{{ $errors->first('handovers') }}</p>
                    @endif
                </div>
            </div>
            <div class="mb-2">
                <label for="subject" class="w-full form-label mb-1">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="w-full form-input" placeholder="Subject of the letter">
                @if($errors->has('subject'))
                    <p class="text-red-500">{{ $errors->first('subject') }}</p>
                @endif
            </div>
            <div class="mb 2">
                <label for="priority" class="w-full form-label mb-1">Letter Priority</label>
                <select class="w-full form-input" name="priority">
                    <option value="">None</option>
                    <option value="1" {{ old('priority') == '1' ? 'selected' : ''}}>High</option>
                    <option value="2" {{ old('priority') == '2' ? 'selected' : ''}}>Medium</option>
                    <option value="3" {{ old('priority') == '3' ? 'selected' : ''}}>Low</option>
                </select>
                @if($errors->has('priority'))
                    <p class="text-red-500">{{ $errors->first('priority') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="description" class="w-full form-label mb-1">Description</label>
                <textarea name="description" id="description"
                placeholder="What is this letter about?"
                class="w-full form-input" rows="3">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <p class="text-red-500">{{ $errors->first('description') }}</p>
                @endif
            </div>
            <div class="mb-2">
                <label for="file" class="w-full form-label mb-1">Upload Letter</label>
                <input type="file" name="attachments[]" accept="image/*, application/pdf" class="w-full">
                @if($errors->has('file'))
                    <p class="text-red-500">{{ $errors->first('file') }}</p>
                @endif
            </div>
            <div class="mb-3">
                <button type="submit" class="w-full btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
@endsection

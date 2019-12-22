@extends('layouts.master')
@section('body')
<div class="page-card max-w-lg mx-auto mt-4">
    <div class="flex items-baseline">
        <h1 class="page-header">Update Outgoing Letter</h1>
        <span class="px-2 rounded text-lg uppercase text-white bg-blue-600">{{ $outgoing_letter->serial_no }}</span>
    </div>
    <form action="{{ route('outgoing_letters.update', $outgoing_letter) }}" method="POST" class="px-6" enctype="multipart/form-data">
        @csrf_token
        @method('PATCH')
        <div class="mb-2">
            <label for="date" class="w-full form-label mb-1">Sent Date<span class="h-current text-red-500 text-lg">*</span></label>
            <input type="text" name="date" value="{{ old('date') ?? $outgoing_letter->date->format('Y-m-d') }}" class="w-full form-input" placeholder="YYYY-MM-DD"
                onfocus="this.type='date'" onblur="this.type='text'">
            @if($errors->has('date'))
            <p class="text-red-500">{{ $errors->first('date') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="type" class="w-full form-label mb-1">Letter Type</label>
            <label class="w-full form-label disable-input bg-gray-400 font-normal text-base">{{ old('type') ?? $outgoing_letter->type }}</label>
            <input type="hidden" name="type" value="{{ old('type') ?? $outgoing_letter->type }}">
            @if($errors->has('type'))
            <p class="text-red-500">{{ $errors->first('type') }}</p>
            @endif
        </div>
        <div class="flex -mx-2 mb-2">
            <div class="mx-2">
                <label for="sender" class="w-full form-label mb-1">Sender<span class="h-current text-red-500 text-lg">*</span></label>
                <vue-typeahead name="sender_id"
                    source="/api/users"
                    find-source="/api/users/{value}"
                    limit="5"
                    value="{{ old('sender_id') ?? $outgoing_letter->sender_id }}"
                    placeholder="Sender">
                </vue-typeahead>
                @if($errors->has('sender_id'))
                <p class="text-red-500">{{ $errors->first('sender_id') }}</p>
                @endif
            </div>
            <div class="mx-2">
                <label for="recipient" class="w-full form-label mb-1">Recipient<span class="h-current text-red-500 text-lg">*</span></label>
                <input type="text" name="recipient" value="{{ old('recipient') ?? $outgoing_letter->recipient }}" class="w-full form-input"
                    placeholder="Recipient (Sent to)">
                @if($errors->has('recipient'))
                <p class="text-red-500">{{ $errors->first('recipient') }}</p>
                @endif
            </div>
        </div>
        <div class="mb-2">
            <label for="subject" class="w-full form-label mb-1">Subject<span class="h-current text-red-500 text-lg">*</span></label>
            <input subject="text" name="subject" value="{{ old('subject') ?? $outgoing_letter->subject }}" class="w-full form-input">
            @if($errors->has('subject'))
                <p class="text-red-500">{{ $errors->first('subject') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="description" class="w-full form-label mb-1">Description</label>
            <textarea name="description" id="description" placeholder="What this letter is about?"
                class="w-full form-input" rows="3">{{ old('description') ?? $outgoing_letter->description }}</textarea>
            @if($errors->has('description'))
            <p class="text-red-500">{{ $errors->first('description') }}</p>
            @endif
        </div>
        @if($outgoing_letter->type == "Bill")
            <div class="mb-2">
                <label for="amount" class="w-full form-label mb-1">Amount</label>
                <input type="number" name="amount" step="0.01" value="{{ old('amount') ?? $outgoing_letter->amount }}" class=" w-full form-input"
                    placeholder="Amount (INR)">
                @if($errors->has('amount'))
                <p class="text-red-500">{{ $errors->first('amount') }}</p>
                @endif
            </div>
        @endif
        <div class="mb-2">
            <label for="attachments" class="w-full form-label mb-1">Attachments<span class="h-current text-red-500 text-lg">*</span></label>
            @if($outgoing_letter->attachments->count())
                <h5>Related Attachments</h5>
            @else
                <h5>No Attachments Uploaded</h5>
            @endif
            <div class="flex flex-wrap -mx-2 mt-2">
                @foreach ($outgoing_letter->attachments as $attachment)
                <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
                    <a href="{{ route('attachments.show', $attachment) }}" target="__blank" class="inline-flex items-center mr-1">
                        <feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">View Attachment</feather-icon>
                        <span>{{ $attachment->original_name }}</span>
                    </a>
                    <button type="submit"
                        form="remove-attachment"
                        formaction="{{ route('attachments.destroy', $attachment) }}"
                        class="p-1 rounded hover:bg-red-500 hover:text-white">
                        <feather-icon name="x" class="h-4" stroke-width="2">Delete Attachment</feather-icon>
                    </button>
                </div>
                @endforeach
            </div>
            <div class="flex -mx-2 mb-6">
                <div class="mx-2">
                    <label for="pdf" class="w-full form-label mb-1">Upload PDF copy</label>
                    <input type="file" name="attachments[]" accept="application/pdf" class="w-full">
                    @if($errors->has('pdf'))
                        <p class="text-red-500">{{ $errors->first('pdf') }}</p>
                    @endif
                </div>
                <div class="mx-2">
                    <label for="scan" class="w-full form-label mb-1">Upload scanned copy</label>
                    <input type="file" name="attachments[]" accept="image/*, application/pdf" class="w-full">
                    @if($errors->has('scan'))
                        <p class="text-red-500">{{ $errors->first('scan') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="mb-3">
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

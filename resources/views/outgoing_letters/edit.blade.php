@extends('layouts.master')
@section('body')
<div class="page-card max-w-lg mx-auto my-4">
    <div class="flex items-baseline">
        <h1 class="page-header">Update Outgoing Letter</h1>
    </div>
    <div class="flex items-baseline mb-4 px-6">
        <span class="px-2 py-1 rounded text-xs uppercase text-white mr-2 font-bold bg-gray-800">{{ $outgoing_letter->serial_no }}</span>
        <span class="px-2 py-1 rounded text-xs uppercase text-white ml-2 font-bold {{
                $outgoing_letter->type == 'Bill'
                ? 'bg-blue-600'
                : ($outgoing_letter->type == 'Notesheet' ? 'bg-teal-600' : 'bg-gray-800')
            }}">
            {{ $outgoing_letter->type }}
        </span>
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
            @if($outgoing_letter->attachments->count())
            <label for="attachments" class="w-full form-label mb-1">Add Attachments</label>
            <div class="flex flex-wrap -mx-2 mb-2">
                @foreach ($outgoing_letter->attachments as $attachment)
                <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
                    <a href="{{ route('attachments.show', $attachment) }}" target="__blank"
                        class="inline-flex items-center mr-1">
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
            @else
            <label for="attachments" class="w-full form-label mb-1">
                Upload Attachments <span class="h-current text-red-500 text-lg">*</span>
            </label>
            @endif
            <div class="flex -mx-2 mb-1">
                <div class="mx-2">
                    <label for="pdf" class="w-full form-label mb-1">Upload PDF copy</label>
                    <input id="pdf" type="file" name="attachments[]" accept="application/pdf" class="w-full">
                </div>
                <div class="mx-2">
                    <label for="scan" class="w-full form-label mb-1">Upload scanned copy</label>
                    <input id="scan" type="file" name="attachments[]" accept="image/*" class="w-full">
                </div>
            </div>
            @if($errors->has('attachments'))
                <p class="font-bold text-red-500">{{ $errors->first('attachments') }}</p>
            @endif
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

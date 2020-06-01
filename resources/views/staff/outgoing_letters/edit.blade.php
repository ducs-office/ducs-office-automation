@extends('layouts.master')
@section('body')
<div class="page-card max-w-xl mx-auto my-4">
    <div class="page-header flex items-baseline">
        <h2 class="mr-6">Update Outgoing Letter</h2>
        <span class="px-2 py-1 rounded uppercase text-sm text-white ml-auto mr-1 font-bold font-mono bg-gray-800">
            {{ $letter->serial_no }}
        </span>
        <span class="px-2 py-1 rounded uppercase text-sm text-white ml-1 font-bold {{ $letter->type->contextCSS() }}">
            {{ $letter->type }}
        </span>
    </div>
    <form action="{{ route('staff.outgoing_letters.update', $letter) }}" method="POST" class="px-6" enctype="multipart/form-data">
        @csrf_token
        @method('PATCH')
        <div class="mb-2">
            <label for="date" class="w-full form-label mb-1">
                Sent Date <span class="text-red-600">*</span>
            </label>
            <input type="date"
                name="date"
                class="w-full form-input{{  $errors->has('date') ? ' border-red-600' : ''}}"
                value="{{ old('date', $letter->date->format('Y-m-d')) }}">
            @if($errors->has('date'))
                <p class="mt-1 text-red-600">{{ $errors->first('date') }}</p>
            @endif
        </div>
        <div class="flex -mx-2 mb-2">
            <div class="mx-2">
                <label for="sender" class="w-full form-label mb-1">
                    Sender <span class="text-red-600">*</span>
                </label>
                <vue-typeahead name="sender_id"
                    source="/api/users"
                    find-source="/api/users/{value}"
                    limit="5"
                    value="{{ old('sender_id', $letter->sender_id) }}"
                    placeholder="Sender"
                    :has-error="{{ $errors->has('sender_id') ? 'true' : 'false'}}">
                </vue-typeahead>
                @if($errors->has('sender_id'))
                    <p class="mt-1 text-red-600">{{ $errors->first('sender_id') }}</p>
                @endif
            </div>
            <div class="mx-2">
                <label for="recipient" class="w-full form-label mb-1">
                    Recipient <span class="text-red-600">*</span>
                </label>
                <input type="text" name="recipient"
                    class="w-full form-input{{ $errors->has('recipient') ? ' border-red-600' : ''}}"
                    value="{{ old('recipient', $letter->recipient) }}"
                    placeholder="Recipient (Sent to)">
                @if($errors->has('recipient'))
                    <p class="mt-1 text-red-600">{{ $errors->first('recipient') }}</p>
                @endif
            </div>
        </div>
        <div class="mb-2">
            <label for="subject" class="w-full form-label mb-1">
                Subject <span class="text-red-600">*</span>
            </label>
            <input type="text"
                name="subject"
                class="w-full form-input{{ $errors->has('subject') ? ' border-red-600' : ''}}"
                value="{{ old('subject', $letter->subject) }}">
            @if($errors->has('subject'))
                <p class="mt-1 text-red-600">{{ $errors->first('subject') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="description" class="w-full form-label mb-1">Description</label>
            <textarea  id="description"
                name="description"
                placeholder="What this letter is about?"
                class="w-full form-input{{ $errors->has('description') ? ' border-red-600' : ''}}"
                rows="3">{{ old('description', $letter->description) }}</textarea>
            @if($errors->has('description'))
            <p class="mt-1 text-red-600">{{ $errors->first('description') }}</p>
            @endif
        </div>
        @if($letter->type != "General")
            <div class="mb-2">
                <label for="amount" class="w-full form-label mb-1">Amount</label>
                <input id="amount"
                    name="amount"
                    type="number" step="0.01"
                    value="{{ old('amount', $letter->amount) }}"
                    class=" w-full form-input{{ $errors->has('amount') ? ' border-red-600' : ''}}"
                    placeholder="Amount (INR)">
                @if($errors->has('amount'))
                <p class="mt-1 text-red-600">{{ $errors->first('amount') }}</p>
                @endif
            </div>
        @endif
        <div class="mb-2">
            @if($letter->attachments->count())
            <label for="attachments" class="w-full form-label mb-1">Add Attachments</label>
            <div class="flex flex-wrap -mx-2 mb-2">
                @foreach ($letter->attachments as $attachment)
                <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
                    <a href="{{ route('staff.attachments.show', $attachment) }}" target="__blank"
                        class="inline-flex items-center mr-1">
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
            @else
            <label for="attachments" class="w-full form-label mb-1">
                Upload Attachments <span class="text-red-600">*</span>
            </label>
            @endif
            <div class="flex mb-1">
                <v-file-input id="pdf" name="attachments[]" accept="application/pdf" class="flex-1 form-input overflow-hidden mr-2"
                    placeholder="Choose a PDF file">
                    <template v-slot="{ label }">
                        <div class="w-full inline-flex items-center">
                            <x-feather-icon name="upload" class="h-4 mr-2 text-gray-700 flex-shrink-0"></x-feather-icon>
                            <span v-text="label" class="truncate"></span>
                        </div>
                    </template>
                </v-file-input>
                <v-file-input id="scan" name="attachments[]" accept="image/*" class="flex-1 form-input overflow-hidden"
                    placeholder="Choose a Scanned Image">
                    <template v-slot="{ label }">
                        <div class="w-full inline-flex items-center">
                            <x-feather-icon name="upload" class="h-4 mr-2 text-gray-700 flex-shrink-0"></x-feather-icon>
                            <span v-text="label" class="truncate"></span>
                        </div>
                    </template>
                </v-file-input>
            </div>
            @if($errors->has('attachments'))
                <p class="mt-1 text-red-600">{{ $errors->first('attachments') }}</p>
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

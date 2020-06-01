@extends('layouts.master')
@section('body')
<div class="page-card max-w-xl my-4 mx-auto">
    <h2 class="page-header px-6">New Outgoing Letter</h2>
    <form action="{{ route('staff.outgoing_letters.store') }}" method="POST" class="px-6" enctype="multipart/form-data">
        @csrf_token
        <div class="mb-2">
            <label for="date" class="w-full form-label mb-1">
                Sent Date <span class="text-red-600">*</span>
            </label>
            <input type="date"
                name="date"
                value="{{ old('date') }}"
                class="w-full form-input{{ $errors->has('date') ? ' border-red-600' : '' }}"
                required>
            @if($errors->has('date'))
                <p class="mt-1 text-red-600">{{ $errors->first('date') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="type" class="w-full form-label mb-1">
                Letter Type <span class="text-red-600">*</span>
            </label>
            <select name="type"
                class="w-full form-select{{ $errors->has('type') ? ' border-red-600' : '' }}"
                onchange="value === '{{ App\Types\OutgoingLetterType::GENERAL }}' ? amount.disabled = true : amount.disabled = false;"
                required>
                @foreach ($types as $type)
                <option value="{{ $type }}"
                    {{ old('type', App\Types\OutgoingLetterType::GENERAL) === $type ? ' selected' : '' }}>
                    {{ $type }}
                </option>
                @endforeach
            </select>
            @if($errors->has('type'))
                <p class="mt-1 text-red-600">{{ $errors->first('type') }}</p>
            @endif
        </div>
        <div class="flex -mx-2 mb-2">
            <div class="mx-2">
                <label for="sender" class="w-full form-label mb-1">
                    Sender <span class="text-red-600">*</span>
                </label>
                <vue-typeahead
                    name="sender_id"
                    source="/api/users"
                    find-source="/api/users/{value}"
                    limit="5"
                    value="{{ old('sender_id') }}"
                    placeholder="Sender"
                    :has-errors="{{ $errors->has('sender_id') ? 'true' : 'false'}}">
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
                    class="w-full form-input{{ $errors->has('recipient') ? ' border-red-600' : '' }}"
                    value="{{ old('recipient') }}"
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
            <input subject="text" name="subject"
                value="{{ old('subject') }}"
                class="w-full form-input{{ $errors->has('subject') ? ' border-red-600' : '' }}"
                placeholder="Subject of the letter">
            @if($errors->has('subject'))
                <p class="mt-1 text-red-600">{{ $errors->first('subject') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="description" class="w-full form-label mb-1">Description</label>
            <textarea name="description" id="description"
                placeholder="What this letter is about?"
                class="w-full form-input{{ $errors->has('description') ? ' border-red-600' : '' }}"
                rows="3">{{ old('description') }}</textarea>
            @if($errors->has('description'))
                <p class="mt-1 text-red-600">{{ $errors->first('description') }}</p>
            @endif
        </div>
        <div class="mb-3">
            <label for="amount" class="w-full form-label mb-1">Amount</label>
            <input type="number" name="amount" step="0.01"
                class=" w-full form-input"
                placeholder="Amount (INR)"
                value="{{ old('amount') }}"
                {{ old('type') === 'General' || old('type') === null ?  'disabled' : ''}}>
            @if($errors->has('amount'))
                <p class="mt-1 text-red-600">{{ $errors->first('amount') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label class="form-label mb-1">
                Attachments (Min: 1) <span class="text-red-600">*</span>
            </label>
            <div class="flex items-center">
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
        </fieldset>
        <div class="mt-6">
            <button type="submit" class="w-full btn btn-magenta">Create</button>
        </div>
    </form>
</div>
@endsection

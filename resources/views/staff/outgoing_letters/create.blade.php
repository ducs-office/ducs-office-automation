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
                class="w-full form-input{{ $errors->has('type') ? ' border-red-600' : '' }}"
                onchange="value === 'General' ? amount.disabled = true : amount.disabled = false;"
                required>
                <option value="General"{{ old('type', 'General') === 'General' ? ' selected' : '' }}>General</option>
                <option value="Bill"{{ old('type') === 'Bill' ? ' selected' : '' }}>Bill</option>
                <option value="Notesheet"{{ old('type') === 'Notesheet' ? ' selected' : '' }}>Notesheet</option>
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
        <div class="mb-2">
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
            <label for="attachments" class="w-full form-label mb-1">
                Attachments <span class="text-red-600">*</span>
            </label>
            <div class="flex items-center -mx-2">
                <div class="mx-2">
                <label for="pdf"
                    class="w-full form-label {{ $errors->has('attachments') ? 'border-red-600 ' : '' }}mb-1">
                    Upload PDF copy
                </label>
                    <input type="file" name="attachments[]" accept="application/pdf" class="w-full">
                </div>
                <div class="mx-2">
                    <label for="scan"
                        class="w-full form-label {{ $errors->has('attachments') ? 'border-red-600 ' : '' }}mb-1">
                        Upload scanned copy
                    </label>
                    <input type="file" name="attachments[]" accept="image/*" class="w-full">
                </div>
            </div>
            @if($errors->has('attachments'))
                <p class="mt-1 text-red-600">{{ $errors->first('attachments') }}</p>
            @endif
        </div>
        <div class="mt-6">
            <button type="submit" class="w-full btn btn-magenta">Create</button>
        </div>
    </form>
</div>
@endsection

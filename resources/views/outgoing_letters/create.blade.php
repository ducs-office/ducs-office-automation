@extends('layouts.master')
@section('body')
<div class="page-card max-w-lg mt-4 mx-auto">
    <h1 class="page-header px-6">New Outgoing Letter</h1>
    <form action="/outgoing-letters" method="POST" class="px-6">
        @csrf
        <div class="mb-2">
            <label for="date" class="w-full form-label mb-1">Sent Date</label>
            <input type="text" name="date" value="{{ old('date') }}" class="w-full form-input" placeholder="YYYY-MM-DD" onfocus="this.type='date'" onblur="this.type='text'">
            @if($errors->has('date'))
                <p class="text-red-500">{{ $errors->first('date') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="type" class="w-full form-label mb-1">Letter Type</label>
            <input type="text" name="type" value="{{ old('type') }}" class="w-full form-input" placeholder="e.g. Bill, Invitation Letter, Progress Report">
            @if($errors->has('type'))
                <p class="text-red-500">{{ $errors->first('type') }}</p>
            @endif
        </div>
        <div class="flex -mx-2 mb-2">
            <div class="mx-2">
                <label for="sender" class="w-full form-label mb-1">Sender</label>
                <vue-typeahead 
                    name="sender_id" 
                    source="/api/users"
                    find-source="/api/users/{value}"
                    limit="5"
                    value="{{ old('sender_id') }}" 
                    placeholder="Sender">
                </vue-typeahead>
                @if($errors->has('sender_id'))
                    <p class="text-red-500">{{ $errors->first('sender_id') }}</p>
                @endif
            </div>
            <div class="mx-2">
                <label for="recipient" class="w-full form-label mb-1">Recipient</label>
                <input type="text" name="recipient" value="{{ old('recipient') }}" class="w-full form-input" placeholder="Recipient (Sent to)">
                @if($errors->has('recipient'))
                    <p class="text-red-500">{{ $errors->first('recipient') }}</p>
                @endif
            </div>
        </div>
        <div class="mb-2">
            <label for="description" class="w-full form-label mb-1">Description</label>
            <textarea name="description" id="description" 
            placeholder="What this letter is about?"
            class="w-full form-input" rows="3">{{ old('description') }}</textarea>
            @if($errors->has('description'))
                <p class="text-red-500">{{ $errors->first('description') }}</p>
            @endif
        </div>
        <div class="mb-6">
            <label for="amount" class="w-full form-label mb-1">Amount</label>
            <input type="number" name="amount" step="0.01" value="{{ old('amount') }}" class=" w-full form-input" placeholder="Amount (INR)">
            @if($errors->has('amount'))
                <p class="text-red-500">{{ $errors->first('amount') }}</p>
            @endif
        </div>
        <div class="mb-3">
            <button type="submit" class="w-full btn btn-magenta">Create</button>
        </div>
    </form>
</div>
@endsection
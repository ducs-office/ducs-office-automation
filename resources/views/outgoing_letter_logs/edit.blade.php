@extends('layouts.master')
@section('body')
<div class="page-card max-w-lg mx-auto">
    <h1 class="page-header">Update Outgoing Letter Log</h1>
    <form action="/outgoing-letter-logs/{{ $outgoing_letter->id }}" method="POST" class="px-6">
        @csrf
        @method('PATCH')
        <div class="mb-2">
            <label for="date" class="w-full form-label">Sent Date</label>
            <input type="text" name="date" value="{{ old('date') ?? $outgoing_letter->date->format('Y-m-d') }}" class="w-full form-input" placeholder="YYYY-MM-DD"
                onfocus="this.type='date'" onblur="this.type='text'">
            @if($errors->has('date'))
            <p class="text-red-500">{{ $errors->first('date') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="type" class="w-full form-label">Letter Type</label>
            <input type="text" name="type" value="{{ old('type') ?? $outgoing_letter->type }}" class="w-full form-input"
                placeholder="e.g. Bill, Invitation Letter, Progress Report">
            @if($errors->has('type'))
            <p class="text-red-500">{{ $errors->first('type') }}</p>
            @endif
        </div>
        <div class="flex -mx-2 mb-2">
            <div class="mx-2">
                <label for="sender" class="w-full form-label">Sender</label>
                <input type="text" name="sender_id" value="{{ old('sender_id') ?? $outgoing_letter->sender_id }}" class="w-full form-input"
                    placeholder="Sender's ID">
                @if($errors->has('sender_id'))
                <p class="text-red-500">{{ $errors->first('sender_id') }}</p>
                @endif
            </div>
            <div class="mx-2">
                <label for="recipient" class="w-full form-label">Recipient</label>
                <input type="text" name="recipient" value="{{ old('recipient') ?? $outgoing_letter->recipient }}" class="w-full form-input"
                    placeholder="Recipient (Sent to)">
                @if($errors->has('recipient'))
                <p class="text-red-500">{{ $errors->first('recipient') }}</p>
                @endif
            </div>
        </div>
        <div class="mb-2">
            <label for="description" class="w-full form-label">Description</label>
            <textarea name="description" id="description" placeholder="What this letter is about?"
                class="w-full form-input" rows="3">{{ old('description') ?? $outgoing_letter->description }}</textarea>
            @if($errors->has('description'))
            <p class="text-red-500">{{ $errors->first('description') }}</p>
            @endif
        </div>
        <div class="mb-4">
            <label for="amount" class="w-full form-label">Amount</label>
            <input type="number" name="amount" step="0.01" value="{{ old('amount') ?? $outgoing_letter->amount }}" class=" w-full form-input"
                placeholder="Amount (INR)">
            @if($errors->has('amount'))
            <p class="text-red-500">{{ $errors->first('amount') }}</p>
            @endif
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-black">Submit</button>
        </div>
    </form>
</div>
@endsection
@extends('layouts.master')
@section('body')
    <h1 class="mb-8">New Outgoing Letter Log</h1>
    <form action="/outgoing-letter-logs" method="POST">
        @csrf
        <div class="mb-2">
            <label for="date">Sent Date</label>
            <input type="date" name="date" value="{{ old('date') }}">
            @if($errors->has('date'))
                <p class="text-red-500">{{ $errors->first('date') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="type">Letter Type</label>
            <input type="text" name="type" value="{{ old('type') }}">
            @if($errors->has('type'))
                <p class="text-red-500">{{ $errors->first('type') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="sender">Sender</label>
            <input type="text" name="sender_id" value="{{ old('sender_id') }}">
            @if($errors->has('sender_id'))
                <p class="text-red-500">{{ $errors->first('sender_id') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="recipient">Recipient</label>
            <input type="text" name="recipient" value="{{ old('recipient') }}">
            @if($errors->has('recipient'))
                <p class="text-red-500">{{ $errors->first('recipient') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3">{{ old('description') }}</textarea>
            @if($errors->has('description'))
                <p class="text-red-500">{{ $errors->first('description') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="amount">Amount</label>
            <input type="number" name="amount" value="{{ old('amount') }}">
            @if($errors->has('amount'))
                <p class="text-red-500">{{ $errors->first('amount') }}</p>
            @endif
        </div>
        <div class="mb-3">
            <button type="submit">Submit</button>
        </div>
    </form>
@endsection
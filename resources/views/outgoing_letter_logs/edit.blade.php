@extends('layouts.master')
@section('body')
    <h1 class="mb-8">Update Outgoing Letter Log</h1>
    <form action="/outgoing-letter-logs/{{$outgoing_letter->id}}" method="POST">
        @csrf
        @method('PATCH') 
        {{-- // Method spoofing. Provided by laravel --}}
        {{-- <input type="hidden" name="method" value="POST"> Laravel takes care of rest --}}
        <div class="mb-2">
            <label for="date">Sent Date</label>
            <input type="date" name="date" value="{{ old('date') ?? $outgoing_letter->date->format('Y-m-d') }}">
            @if($errors->has('date'))
                <p class="text-red-500">{{ $errors->first('date') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="type">Letter Type</label>
            <input type="text" name="type" value="{{old('type') ?? $outgoing_letter->type }}">
            @if($errors->has('type'))
                <p class="text-red-500">{{ $errors->first('type') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="sender">Sender</label>
            <input type="text" name="sender_id" value="{{old('sender_id') ?? $outgoing_letter->sender_id }}">
            @if($errors->has('sender_id'))
                <p class="text-red-500">{{ $errors->first('sender_id') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="recipient">Recipient</label>
            <input type="text" name="recipient" value="{{old('recipient') ?? $outgoing_letter->recipient }}">
            @if($errors->has('recipient'))
                <p class="text-red-500">{{ $errors->first('recipient') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3">{{old('description') ?? $outgoing_letter->description }}</textarea>
            @if($errors->has('description'))
                <p class="text-red-500">{{ $errors->first('description') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label for="amount">Amount</label>
            <input type="number" name="amount" value="{{old('amount') ?? $outgoing_letter->amount }}">
            @if($errors->has('amount'))
                <p class="text-red-500">{{ $errors->first('amount') }}</p>
            @endif
        </div>
        <div class="mb-3">
            <button type="submit">Submit</button>
        </div>
    </form>
@endsection
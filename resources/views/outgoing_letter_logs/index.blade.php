@extends('layouts.master')
@section('body')
    <h1 class="mb-8">Outgoing Letter Logs</h1>
    <div class="mb-8">
        <a href="/outgoing-letter-logs/create" class="text-blue-500 hover:underline">Create</a>
    </div>
    <div class="mb-3">
        <h4 class="mb-1">Filters:</h4>
        <form method="GET">
            <label class="font-bold" for="before">Before:</label>
            <input type="date" name="before" placeholder="Before date" class="mr-4">

            <label class="font-bold" for="after">After</label>
            <input type="date" name="after" placeholder="Before date">

            <button type="submit">Filter</button>
        </form>
    </div>
    <table>
        <thead>
            <th>Date</th>
            <th>Sender</th>
            <th>Type</th>
            <th>Description</th>
            <th>Amount</th>
        </thead>
        <tbody>
            @foreach($outgoing_letter_logs as $letter)
            <tr>
                <td>{{ $letter->date->format('Y-m-d') }}</td>
                <td>{{ $letter->sender->name }}</td>
                <td>{{ $letter->type }}</td>
                <td>{{ $letter->description }}</td>
                <td>{{ $letter->amount ?? 'NA' }}</td>
                <td><a href = "/outgoing-letter-logs/{{$letter->id}}" class="text-blue-500 hover:underline">Edit</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
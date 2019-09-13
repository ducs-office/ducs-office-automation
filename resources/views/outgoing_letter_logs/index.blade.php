@extends('layouts.master')
@section('body')
    <div class="page-card">
        <h1 class="page-header">Outgoing Letter Logs</h1>
        
        <div class="flex justify-between items-end mb-8 bg-gray-200 py-2 px-6">
            <form method="GET" class="flex items-end">
                <input type="text" name="after" 
                    placeholder="After date"
                    class="w-full form-input mr-4" 
                    onfocus="this.type='date'"
                    onblur="this.type='text'">

                <input type="text" name="before" 
                    placeholder="Before date"
                    class="w-full form-input mr-4" 
                    onfocus="this.type='date'"
                    onblur="this.type='text'">
                    
                <button type="submit" class="btn btn-black">Filter</button>
            </form>
            <a href="/outgoing-letter-logs/create"
                class="btn btn-blue text-lg">
                Create
            </a>
        </div>
        <table class="w-full border border-t-0 border-collapse text-left">
            <thead>
                <th class="bg-gray-200 pl-6 pr-3 py-2">Date</th>
                <th class="bg-gray-200 px-3 py-2">Sender</th>
                <th class="bg-gray-200 px-3 py-2">Recipient</th>
                <th class="bg-gray-200 px-3 py-2">Type</th>
                <th class="bg-gray-200 px-3 py-2">Description</th>
                <th class="bg-gray-200 px-3 py-2 text-right">Amount</th>
                <th class="bg-gray-200 pl-3 pr-6 py-2 text-right">Options</th>
            </thead>
            <tbody>
                @foreach($outgoing_letter_logs as $letter)
                <tr class="hover:bg-gray-100">
                    <td class="pl-6 pr-3 py-1 border-b table-fit">{{ $letter->date->format('Y-m-d') }}</td>
                    <td class="px-3 py-1 border-b table-fit">{{ $letter->sender->name }}</td>
                    <td class="px-3 py-1 border-b table-fit">{{ $letter->recipient }}</td>
                    <td class="px-3 py-1 border-b table-fit">{{ $letter->type }}</td>
                    <td class="px-3 py-1 border-b max-w-2xs truncate" title="{{ $letter->description }}">{{ $letter->description }}</td>
                    <td class="px-3 py-1 border-b table-fit text-right">
                        {{ $letter->amount ? number_format($letter->amount, 2) : 'NA' }}
                    </td>
                    <td class="pl-3 pr-6 py-1 border-b text-right flex justify-between">
                        <a href="/outgoing-letter-logs/{{$letter->id}}" 
                            class="p-1 btn btn-blue"
                            title="Edit">
                            <feather-icon name="edit-3" class="h-current">Edit</feather-icon>
                        </a>
                        <form method = "POST" >
                            @csrf
                            @method('DELETE')
                            <button name = "letter_id" value = {{$letter->id}} 
                                type = "submit"
                                class="p-1 border-2 bg-red-700 text-white border rounded">
                                <feather-icon name="trash-2" class="h-current">Delete</feather-icon>    
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
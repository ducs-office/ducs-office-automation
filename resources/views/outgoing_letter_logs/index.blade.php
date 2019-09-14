@extends('layouts.master')
@section('body')
    <div class="m-4 page-card pb-2 overflow-x-auto">
        <div class="flex items-baseline px-6 mb-4">
            <h1 class="page-header mb-0 px-0 mr-4">Outgoing Letter Logs</h1>
            <a href="/outgoing-letter-logs/create" class="btn btn-magenta is-sm shadow-inset">
                Create
            </a>
        </div>
        
        <div class="flex justify-between items-end py-2 px-6">
            <form method="GET" class="flex items-end">
                <input type="text" name="after" 
                    placeholder="After date"
                    class="form-input is-sm mr-4" 
                    onfocus="this.type='date'"
                    onblur="this.type='text'">

                <input type="text" name="before" 
                    placeholder="Before date"
                    class="form-input is-sm mr-4" 
                    onfocus="this.type='date'"
                    onblur="this.type='text'">
                    
                <button type="submit" class="btn btn-black is-sm">Apply Filter</button>
            </form>
        </div>
        <table class="max-w-full border border-t-0 border-collapse text-left">
            <thead>
                <th class="bg-gray-200 pl-6 py-2">S.NO</th>
                <th class="bg-gray-200 px-3 py-2">Date</th>
                <th class="bg-gray-200 px-3 py-2">Sender</th>
                <th class="bg-gray-200 px-3 py-2">Recipient</th>
                <th class="bg-gray-200 px-3 py-2">Type</th>
                <th class="bg-gray-200 pl-3 pr-6 py-2 text-right">Options</th>
            </thead>
            <tbody>
                @foreach($outgoing_letter_logs as $letter)
                <tr class="hover:bg-gray-100">
                    <td class="pl-6 pr-3 py-1 border-b table-fit">DU/CS/{{ str_pad($letter->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-3 py-1 border-b table-fit">{{ $letter->date->format('d M, Y') }}</td>
                    <td class="px-3 py-1 border-b">{{ $letter->sender->name }}</td>
                    <td class="px-3 py-1 border-b">{{ $letter->recipient }}</td>
                    <td class="px-3 py-1 border-b table-fit">{{ $letter->type }}</td>
                    <td class="pl-3 pr-6 py-1 border-b table-fit text-right">
                        <a href="/outgoing-letter-logs/{{$letter->id}}" 
                            class="p-1 btn btn-blue"
                            title="Edit">
                            <feather-icon name="edit-3" class="h-current">Edit</feather-icon>
                        </a>
                        <form method = "POST" action="/outgoing-letter-logs/{{$letter->id}}" >
                            @csrf
                            @method('DELETE')
                            <button 
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
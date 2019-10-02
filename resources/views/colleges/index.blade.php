@extends('layouts.master')
@section('body')
<div class= "m-6 page-card pb-0">
    <div class = "flex items-baseline px-6 pb-4">
        <h1 class="page-header mb-0 px-0 mr-4">Colleges</h1>
        <button class="btn btn-magenta is-sm shadow-inset" @click.prevent = "$modal.show('create-college-form')">
        New
        </button>
    </div>
    <modal name="create-college-form" height="auto">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">New College</h2>
            <form action="/colleges" method="POST" class="flex items-end">
                @csrf
                <div class="flex-1 mr-2">
                    <label for="college_code" class="w-full form-label">College Code</label>
                    <input id="college_code" type="text" name="code" class="w-full form-input">
                </div>
                <div class="flex-1 mr-5">
                    <label for="college_name" class="w-full form-label">College</label>
                    <input id="college_name" type="text" name="name" class="w-full form-input">
                </div>
                <div>
                    <button type="submit" class="btn btn-magenta">Create</button>
                </div>
            </form>
        </div>
    </modal>

    <college-update-modal name ="college-update-modal">@csrf @method('patch')</college-update-modal>
    @foreach($colleges as $college)
        <div class="px-6 py-2 hover:bg-gray-100 border-b flex justify-between">
            <div class="flex items-baseline">
                <h4 class="text-sm font-semibold text-gray-600 mr-2 w-40">{{$college->code}}</h4>
                <h3 class="text-lg font-bold mr-2 ml-3">
                    {{ucwords($college->name)}}
                </h3>
            </div>
            <div class="flex">
                <button class="p-1 hover:text-blue-500 mr-1" @click.prevent="$modal.show('college-update-modal',{college: {{$college->toJson()}}})">
                   <feather-icon class="h-current" name="edit">Edit</feather-icon>
                </button>
                <form action="/colleges/{{ $college->id }}" method="POST">
                    @csrf @method('delete')
                    <button type="submit" class="p-1 hover:text-red-700">
                        <feather-icon class="h-current" name="trash-2">Trash</feather-icon>
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>
    
@endsection
@extends('layouts.master')
@section('body')
<div class= "m-6 page-card pb-0">
    <div class = "flex items-baseline px-6 pb-4">
        <h1 class="page-header mb-0 px-0 mr-4">Colleges</h1>
        @can('create', App\College::class)
        <button class="btn btn-magenta is-sm shadow-inset" @click.prevent = "$modal.show('create-college-modal')">
        New
        </button>
        @include('colleges.modals.create', [
            'modalName' => 'create-college-modal'
        ])
        @endcan
    </div>

    @can('update', App\College::class)
    @include('colleges.modals.edit', [
        'modalName' => 'edit-college-modal'
    ])
    @endcan
    @foreach($colleges as $college)
        <div class="px-6 py-2 hover:bg-gray-100 border-b flex justify-between">
            <div class="flex items-baseline">
                <h4 class="text-sm font-semibold text-gray-600 mr-2 w-40">{{$college->code}}</h4>
                <h3 class="text-lg font-bold mr-2 ml-3">
                    {{ucwords($college->name)}}
                </h3>
            </div>
            <div class="flex">
                @can('update', App\College::class)
                <button class="p-1 hover:text-blue-500 mr-1" @click.prevent="$modal.show('edit-college-modal',{college: {{$college->toJson()}}})">
                    <feather-icon class="h-current" name="edit">Edit</feather-icon>
                </button>
                @endcan
                @can('delete', App\College::class)
                <form action="{{ route('colleges.destroy', $college) }}" method="POST"
                    onsubmit="return confirm('Do you really want to delete College?');">
                    @csrf_token @method('delete')
                    <button type="submit" class="p-1 hover:text-red-700">
                        <feather-icon class="h-current" name="trash-2">Trash</feather-icon>
                    </button>
                </form>
                @endcan
            </div>
        </div>
    @endforeach
</div>

@endsection

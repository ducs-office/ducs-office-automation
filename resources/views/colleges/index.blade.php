@extends('layouts.master')
@section('body')
<div class= "m-6 page-card pb-0">
    <div class = "flex items-baseline px-6 pb-4">
        <h1 class="page-header mb-0 px-0 mr-4">Colleges</h1>
        @can('create', App\College::class)
        <button class="btn btn-magenta is-sm shadow-inset" @click.prevent = "$modal.show('create-college-form')">
        New
        </button>
        @endcan
    </div>
    @can('create', App\College::class)
    <modal name="create-college-form" height="auto">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">New College</h2>
            <form action="{{ route('colleges.index') }}" method="POST" class="items-end">
                @csrf_token
                <div class="items-baseline">
                    <div class="mb-2">
                        <label for="college_code" class="w-full form-label">College Code<span class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_code" type="text" name="code" class="w-full form-input">
                    </div>
                    <div class="mb-2">
                        <label for="college_name" class="w-full form-label">College Name<span class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_name" type="text" name="name" class="w-full form-input">
                    </div>
                    <div class="mb-2">
                        <label for="programme" class="w-full form-label">Programmes <span class="h-current text-red-500 text-lg">*</span></label>
                        <select name="programmes[]" id="programme" class="w-full form-input" multiple>
                            @foreach ($programmes as $programme)
                                <option value="{{$programme->id}}">{{$programme->code}} - {{ucwords($programme->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-magenta">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </modal>
    @endcan

    @can('update', App\College::class)
    <college-update-modal name ="college-update-modal" :programmes="{{$programmes->toJson()}}">@csrf_token @method('patch') </college-update-modal>
    @endcan
    <college-programmes-view-modal name="college-programmes-view-modal"></college-programmes-view-modal>
    @foreach($colleges as $college)
        <div class="px-6 py-2 hover:bg-gray-100 border-b flex justify-between">
            <div class="flex items-baseline w-2/3">
                <h4 class="text-sm font-semibold text-gray-600 mr-2 w-40">{{$college->code}}</h4>
                <h3 class="text-lg font-bold mr-2 ml-3">
                    {{ucwords($college->name)}}
                </h3>
            </div>
            <div class="flex items-baseline">
                <button class="btn btn-magenta is-sm shadow-inset" @click= "
                    $modal.show('college-programmes-view-modal',{
                    college_programmes: {{$college->programmes->toJson()}}
                })">
                    View Programmes
                </button>
            </div>
            <div class="flex">
                @can('update', App\College::class)
                <button class="p-1 hover:text-blue-500 mr-1" 
                    @click="
                        $modal.show('college-update-modal',{
                            college: {{$college->toJson()}},
                            college_programmes: {{$college->programmes->pluck('id')->toJson()}}
                        })">
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

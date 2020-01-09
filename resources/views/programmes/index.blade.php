@extends('layouts.master')
@section('body')
<div class="m-6 page-card pb-0">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Academic Programmes</h1>
        @can('create', App\Programme::class)
        <button class="btn btn-magenta is-sm shadow-inset" @click.prevent="$modal.show('create-programme-modal')">
            New
        </button>
        @include('programmes.modals.create', [
            'modalName' => 'create-programme-modal',
            'courses' => $courses
        ])
        @endcan
    </div>
    @can('update', App\Programme::class)
    @include('programmes.modals.edit', [
        'modalName' => 'edit-programme-modal',
        'courses' => $courses
    ])
    @endcan
    @foreach ($programmes as $programme)
        <div class="px-6 py-2 hover:bg-gray-100 border-b flex justify-between">
            <div class="flex items-baseline justify-between">
                <span class="px-2 py-1 rounded text-xs uppercase text-white bg-blue-600 mr-2 font-bold">
                    {{ $programme->type === 'Under Graduate(U.G.)' ? 'UG' : 'PG' }}
                </span>
                <p class="px-4">{{ $programme->wef }}</p>
                <h4 class="px-4 text-sm font-semibold text-gray-600 mr-2 w-24">{{ $programme->code }}</h4>
                <h3 class="px-4 text-lg font-bold mr-2">
                    {{ ucwords($programme->name) }}
                </h3>
            </div>
            <div class="flex">
                @can('update', App\Programme::class)
                <button class="p-1 hover:text-blue-500 mr-1" @click.prevent="
                    $modal.show('edit-programme-modal', {
                        programme: {{ $programme->toJson() }}
                    })">
                    <feather-icon class="h-current" name="edit">Edit</feather-icon>
                </button>
                @endcan
                @can('delete', App\Programme::class)
                <form action="{{ route('programmes.destroy', $programme) }}" method="POST"
                    onsubmit="return confirm('Do you really want to delete programme?');">
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

@extends('layouts.master')
@section('body')
<div class="page-card m-6">
    <div class="flex items-center px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Scholars</h1>
        @can('create', App\Scholar::class)
        <button class="btn btn-magenta is-sm shadow-inner"
            @click.prevent="$modal.show('create-scholar-modal')">
            New
        </button>
        @include('staff.scholars.modals.create', [
            'modalName' => 'create-scholar-modal',
        ])
        @endcan
    </div>

    @can('update', App\Scholar::class)
    @include('staff.scholars.modals.edit', [
        'modalName' => 'edit-scholar-modal'
    ])
    @endcan
    @forelse($scholars as $scholar)
        <div class="px-4 py-2 hover:bg-gray-100 border-b flex">
            <div class="px-2 w-64">
                <h3 class="text-lg font-bold mr-2">
                    {{ ucwords($scholar->name) }}
                </h3>
                <h4 class="text-sm font-semibold text-gray-600 mr-2"> {{ $scholar->email }}</h4>
            </div>
            <div class="ml-auto px-2 flex items-center">
                @can('update', App\Scholar::class)
                <button type="submit" class="p-1 hover:text-red-700 mr-2"
                    @click="
                        $modal.show('edit-scholar-modal', {
                            Scholar: {
                                id: {{ $scholar->id }},
                                first_name: {{ json_encode($scholar->first_name) }},
                                last_name: {{ json_encode($scholar->last_name) }},
                                email: {{ json_encode($scholar->email) }},
                            }
                        })">
                    <feather-icon class="h-current" name="edit">Edit</feather-icon>
                </button>
                @endcan
                @can('delete', $scholar)
                <form action="{{ route('staff.scholars.destroy', $scholar) }}" method="POST"
                    onsubmit="return confirm('Do you really want to delete scholar \' {{ $scholar->name }}\'?');">
                    @csrf_token @method('delete')
                    <button type="submit" class="p-1 hover:text-red-700">
                        <feather-icon class="h-current" name="trash-2">Trash</feather-icon>
                    </button>
                </form>
                @endcan
            </div>
        </div>
    @empty
        <div class="py-8 flex flex-col items-center justify-center text-gray-500">
            <feather-icon name="frown" class="h-16"></feather-icon>
            <p class="mt-4 mb-2 font-bold">
                Sorry! No Scholars added yet.
            </p>
        </div>
    @endforelse
</div>
@endsection

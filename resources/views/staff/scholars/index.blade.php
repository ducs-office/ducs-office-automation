@extends('layouts.master')
@section('body')
<div class="page-card m-6">
    <div class="flex items-center px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Scholars</h1>
        @can('create', App\Models\Scholar::class)
        <button class="btn btn-magenta is-sm shadow-inner"
            @click.prevent="$modal.show('create-scholar-modal')">
            New
        </button>
        @include('staff.scholars.modals.create', [
            'modalName' => 'create-scholar-modal',
        ])
        @endcan
    </div>

    @can('update', App\Models\Scholar::class)
    @include('staff.scholars.modals.edit', [
        'modalName' => 'edit-scholar-modal',
    ])
    @endcan
    @include('staff.scholars.modals.replace_supervisor', [
        'modalName' => 'replace-scholar-supervisor-modal',
    ])

    @include('staff.scholars.modals.replace_cosupervisor', [
        'modalName' => 'replace-scholar-cosupervisor-modal',
    ])

    @forelse($scholars as $scholar)
        <div class="hover:bg-gray-100 border-b">
            <div class="flex px-4 py-2 ">
                <div class="px-2 w-64">
                    <h3 class="text-lg font-bold mr-2">
                        {{ ucwords($scholar->name) }}
                    </h3>
                    <h4 class="text-sm font-semibold text-gray-600 mr-2"> {{ $scholar->email }}</h4>
                </div>
                <div class="ml-auto px-2 flex items-center">
                    @can('update', App\Scholar::class)
                    <button type="submit" class="p-1 hover:text-red-700 mr-2"
                        @click="$modal.show('edit-scholar-modal')">
                        <x-feather-icon class="h-current" name="edit">Edit</x-feather-icon>
                    </button>
                    @endcan
                    @can('delete', $scholar)
                    <form action="{{ route('staff.scholars.destroy', $scholar) }}" method="POST"
                        onsubmit="return confirm('Do you really want to delete scholar \' {{ $scholar->name }}\'?');">
                        @csrf_token @method('delete')
                        <button type="submit" class="p-1 hover:text-red-700">
                            <x-feather-icon class="h-current" name="trash-2">Trash</x-feather-icon>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            <div class="flex mb-2">
                <div class="px-6 mr-4 w-1/2">
                    <div class="flex">
                        <h2 class="font-bold underline"> Supervisor </h2>
                        <button type="submit" class="ml-2 text-blue-500"
                            @click="$modal.show('replace-scholar-supervisor-modal')">
                            <x-feather-icon class="h-current" name="refresh-cw">Replace Supervisor</x-feather-icon>
                        </button>
                    </div>
                    <p class="pt-1">{{ $scholar->currentSupervisor->name }}</p>
                </div>
                <div class="px-4 mx-4 w-1/2">
                    <div class="flex">
                        <h2 class="font-bold underline"> Co-Supervisor </h2>
                        <button type="submit" class="ml-2 text-blue-500"
                            @click="$modal.show('replace-scholar-cosupervisor-modal')">
                            <x-feather-icon class="h-current" name="refresh-cw">Replace Co-Supervisor</x-feather-icon>
                        </button>
                    </div>
                    <p class="pt-1">
                        @if ($scholar->cosupervisor)
                        {{ $scholar->cosupervisor->name }}
                        @else
                        {{ "No cosupervisor assigned" }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @empty
        <div class="py-8 flex flex-col items-center justify-center text-gray-500">
            <x-feather-icon name="frown" class="h-16"></x-feather-icon>
            <p class="mt-4 mb-2 font-bold">
                Sorry! No Scholars added yet.
            </p>
        </div>
    @endforelse
</div>
@endsection

@extends('layouts.master')
@section('body')
<div class="m-6">
    <div class="flex items-baseline px-6">
        <h1 class="page-header mb-0 px-0 mr-4">Colleges</h1>
        @can('create', App\Models\College::class)
        <a class="inline-block btn btn-magenta is-sm shadow-inset"
            href="{{ route('staff.colleges.create') }}">New</a>
        @endcan
    </div>
    <div class="bg-gray-200 py-4 space-y-6">
        @foreach($colleges as $college)
            <div class="relative p-6 page-card overflow-visible">
                <h3 class="flex items-center text-lg font-bold mb-1">
                    {{ ucwords($college->name) }}
                    <span class="ml-2 px-2 py-1 bg-gray-900 text-white rounded text-sm font-mono">{{ $college->code }}</span>
                </h3>
                <address class="mb-4">
                    {{ $college->address }}
                </address>
                <a class="text-magenta-700 underline flex items-center my-2" href="{{ $college->website }}" target="__blank">
                    <x-feather-icon name="link" class="h-current mr-2 text-gray-700">External Link</x-feather-icon>
                    <span>Goto College Website</span>
                </a>
                <div class="relative z-10 -ml-8 my-4">
                    <h5 class="relative z-20 pl-8 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">Principal Information</h5>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="mb-4">
                    <p class="font-bold mb-2">{{ $college->principal_name }}</p>
                    <p class="mb-1">
                        <x-feather-icon name="phone" class="inline-block h-current mr-4"></x-feather-icon>
                        @forelse ($college->principal_phones as $phone)
                            <a href="tel:+91{{ $phone }}" class="text-magenta-700 underline mr-2">+91 {{ $phone }}</a>
                        @empty
                            NA
                        @endforelse
                    </p>
                    <p class="mb-1">
                        <x-feather-icon name="at-sign" class="inline-block h-current mr-4"></x-feather-icon>
                        @foreach ($college->principal_emails as $email)
                        <a href="mailto:{{ $email }}" class="text-magenta-700 underline mr-2">{{ $email }}</a>
                        @endforeach
                    </p>
                </div>
                <details class="bg-gray-100 rounded-t border overflow-hidden">
                    <summary class="p-2 bg-gray-200 cursor-pointer outline-none">Programmes</summary>
                    <ul class="flex flex-wrap py-4 list-disc list-inside">
                        @forelse ($college->programmes as $programme)
                            <li class="w-1/2 pl-6 py-2">{{ $programme->name }}</li>
                        @empty
                            <p class="pl-6 flex-1 text-gray-700 font-bold">No Programmes.</p>
                        @endforelse
                    </ul>
                </details>

                <div class="absolute top-0 right-0 mt-4 mr-4 flex">
                    @can('update', App\Models\College::class)
                    <a href="{{ route('staff.colleges.edit', $college) }}" class="p-1 hover:text-blue-500 mr-1">
                        <x-feather-icon class="h-current" name="edit">Edit</x-feather-icon>
                    </a>
                    @endcan
                    @can('delete', App\Models\College::class)
                    <form action="{{ route('staff.colleges.destroy', $college) }}" method="POST"
                        onsubmit="return confirm('Do you really want to delete College?');">
                        @csrf_token @method('delete')
                        <button type="submit" class="p-1 hover:text-red-700">
                            <x-feather-icon class="h-current" name="trash-2">Trash</x-feather-icon>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

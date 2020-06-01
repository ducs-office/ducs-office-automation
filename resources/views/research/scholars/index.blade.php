@extends('layouts.research')
@section('body')
<div class="page-card m-6">
    <div class="flex items-center px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Research Scholars</h1>
    </div>
    @forelse($scholars as $scholar)
        <div class="px-4 py-2 hover:bg-gray-100 border-b flex">
            <div class="px-2 w-64">
                <h3 class="text-lg font-bold mr-2">
                    {{ ucwords($scholar->name) }}
                </h3>
                <h4 class="text-sm font-semibold text-gray-600 mr-2"> {{ $scholar->email }}</h4>
            </div>
            <div class="ml-auto px-2 flex items-center">
                <a href="{{ route('research.scholars.show', $scholar) }}"
                    class="p-1 hover:text-blue-700 mr-2">
                    <x-feather-icon class="h-4" name="eye" stroke-width="2.5">View</x-feather-icon>
                </a>
            </div>
        </div>
    @empty
        <div class="py-8 flex flex-col items-center justify-center text-gray-500">
            <x-feather-icon name="frown" class="h-16"></x-feather-icon>
            <p class="mt-4 mb-2 font-bold">
                You don't supervise any scholars yet.
            </p>
        </div>
    @endforelse
</div>
@endsection

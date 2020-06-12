@extends('layouts.scholar-profile', ['pageTitle' => 'Leaves', 'scholar' => $scholar])
@push('modals')
<livewire:apply-for-leave-modal :error-bag="$errors->default" :scholar="$scholar"/>
<livewire:respond-to-leave-modal :error-bag="$errors->default" :scholar="$scholar"/>
@endpush
@section('body')
<div class="page-card p-6 overflow-visible">
    {{-- <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Leaves
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg> --}}
    {{-- </div> --}}
    {{-- <div class="flex-1"> --}}
    <form id="patch-form" method="POST" class="w-0">
        @csrf_token @method("PATCH")
    </form>
    <ul class="w-full border rounded-lg overflow-hidden mb-4">
        @forelse ($scholar->leaves as $leave)
        @include('_partials.list-items.leave')
        @empty
        <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No Leaves</li>
        @endforelse
    </ul>
    @can('create', [Leave::class, $scholar])
    {{-- <div class="mt-3 text-right"> --}}
        <x-modal.trigger :livewire="['payload' => '']" modal="apply-for-leave-modal"  title="Apply" 
        class="mt-2 w-full btn btn-magenta rounded-lg py-3">
            Apply For Leaves
        </x-modal.trigger>
        {{-- </div> --}}
        @endcan
    {{-- </div> --}}
</div>
@endsection

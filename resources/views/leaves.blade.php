@extends('layouts.scholar-profile', ['pageTitle' => 'Leaves', 'scholar' => $scholar])
@push('modals')
<livewire:apply-for-leave-modal :error-bag="$errors->default" :scholar="$scholar"/>
<livewire:respond-to-leave-modal :error-bag="$errors->default" :scholar="$scholar"/>
@endpush
@section('body')
<div class="page-card p-6 overflow-visible">
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

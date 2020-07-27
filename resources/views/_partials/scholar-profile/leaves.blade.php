@push('modals')
<livewire:apply-for-leave-modal :error-bag="$errors->applyLeave" :scholar="$scholar"/>
<livewire:respond-to-leave-modal :error-bag="$errors->respondLeave" :scholar="$scholar"/>
@endpush
<div class="page-card p-6 overflow-visible">
    <div class="-mx-6 -mt-6 px-6 py-3 border-b mb-6 flex items-center">
        <div class="flex items-center space-x-2">
            <x-feather-icon name="umbrella" class="h-4 w-4"></x-feather-icon>
            <h2 class="text-lg font-bold">Leaves</h2>
        </div>
        @can('create', [Leave::class, $scholar])
        <x-modal.trigger :livewire="['payload' => '']" modal="apply-for-leave-modal"  title="Apply"
        class="ml-auto inline-flex items-center space-x-1 btn btn-magenta px-2 py-1">
            <x-feather-icon name="plus" class="w-4 h-4"></x-feather-icon>
            <span>Apply</span>
        </x-modal.trigger>
        @endcan
    </div>
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
</div>

<x-modal name="{{ $modalName }}" class="p-6 w-1/2" :open="$showModal">
    @if($showModal)
        <h3 class="text-lg font-bold mb-4">Add Leave</h3>
        @include('_partials.forms.apply-for-leave')
    @else
        <p>Loading...</p>
    @endif
</x-modal>

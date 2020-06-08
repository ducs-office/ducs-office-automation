<x-modal name="{{ $modalName }}" class="p-6 w-1/2" :open="$showModal">
    @if($showModal)
        <h2 class="text-lg font-bold mb-8">Edit Reminder</h2>
        @include('_partials.forms.edit-reminder')
    @else
        <p>Loading...</p>
    @endif
</x-modal>

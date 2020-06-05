<x-modal :name="$modalName" class="p-6 min-w-1/3" :open="$showModal">
    <h2 class="text-lg font-bold mb-8">Update Programme</h2>
    @if($showModal)
        @include('_partials.forms.edit-programme')
    @else
        <p>Loading...</p>
    @endif
</x-modal>

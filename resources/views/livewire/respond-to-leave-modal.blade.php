<x-modal name="{{ $modalName }}" class="p-6 w-1/2" :open="$showModal">
    @if($showModal)
    <h2 class="text-lg font-bold mb-8">Respond To Leave</h2>
    @include('_partials.forms.respond-to-leave')
    @else
        <p>Loading...</p>
    @endif
</x-modal>
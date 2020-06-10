<x-modal name="{{ $modalName }}" class="min-w-1/3 p-6" :open="$showModal">
    @if($showModal)
    @include('_partials.forms.replace-cosupervisor', ['scholar' => $scholar])
    @else
    <p>Loading...</p>
    @endif
</x-modal>

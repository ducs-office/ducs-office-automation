<x-modal name="{{ $modalName }}" class="p-6 w-1/2" :open="$showModal">
    @if($showModal)
    <h2 class="text-lg font-bold mb-8">Co-Authors - {{ $publication->paper_title}}</h2>
        @include('_partials.forms.add-co-authors', $publication)
        <hr class="my-5">
        @include('_partials.list-items.co-author', $publication)
    @else
        <p>Loading...</p>
    @endif
</x-modal>

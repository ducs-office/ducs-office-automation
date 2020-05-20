<x-modal name="{{ $modalName }}" class="p-6 w-1/2">
    @if($showModal)
        <h2 class="text-lg font-bold mb-8">Edit User - {{ $user->name }}</h2>
        @include('_partials.forms.edit-user')
    @else
        <p>Loading...</p>
    @endif
</x-modal>

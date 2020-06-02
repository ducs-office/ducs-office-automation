<x-modal :name="$modalName" class="page-card p-6" >
    <h2 class="text-lg font-bold mb-8">Edit Role</h2>
    @if($showModal)
        @include('_partials.forms.edit-role', [
            'permissions' => $permissions
        ])
    @else
        <p class="py-2">Loading...</p>
    @endif
</x-modal>

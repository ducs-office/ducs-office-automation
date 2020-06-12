@push('modals')
<x-modal name="edit-user-basic-info-modal" class="p-6 w-1/2"
    :open="$errors->update->hasAny(['phone', 'address'])">
    <h2 class="text-lg font-bold mb-3">{{ $user->name }}</h2>
    <h3 class="mb-6 font-bold">Basic Information</h3>
    @include('_partials.forms.edit-user-basic-info')
</x-modal>
@endpush
<div class="flex items-center">
    <h3 class="px-3 text-lg font-bold">
        Basic Information
    </h3>
    @can('updateProfile', $user)
    <x-modal.trigger modal="edit-user-basic-info-modal"  title="Edit"
        class="p-1 ml-auto text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
        <x-feather-icon name="edit" class="h-current mr-2"> Edit </x-feather-icon>
    </x-modal.trigger>
    @endcan
</div>

<div class="mt-4 flex-1">
    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
        <li class="px-4 py-3 flex space-x-4">
            <h4 class="whitespace-no-wrap font-bold w-48">Email</h4>
            <p class="flex-1 text-gray-800"> {{ $user->email }}</p>
        </li>
        <li class="px-4 py-3 flex space-x-4">
            <p class="whitespace-no-wrap font-bold w-48">Contact Number</p>
            <p class="flex-1 text-gray-800">{{ $user->phone ?? '-' }}</p>
        </li>
        <li class="px-4 py-3 flex space-x-4">
            <p class="whitespace-no-wrap font-bold w-48">Address</p>
            <p class="flex-1 text-gray-800">
                {{ $user->address ?? '-'}}
            </p>
        </li>
    </ul>
</div>
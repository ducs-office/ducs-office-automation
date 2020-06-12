@push('modals')
    <x-modal name="edit-scholar-basic-info-modal" class="p-6 w-1/2"
    :open="$errors->update->hasAny(['phone', 'address', 'gender'])">
    <h2 class="text-lg font-bold mb-8">Edit Basic Info - {{ $scholar->name }}</h2>
        @include('_partials.forms.edit-scholar-basic-info' ,[
            'genders' => $genders,
        ])
    </x-modal>
@endpush
<div class="flex items-center">
    <h3 class="px-3 text-lg font-bold">
        Basic Information
    </h3>
    <div class="ml-auto">
        @can('updateProfile', [App\Models\Scholar::class, $scholar])
        <x-modal.trigger modal="edit-scholar-basic-info-modal"
            class="p-1 text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
            <x-feather-icon class="h-5" name="edit-3">Edit</x-feather-icon>
        </x-modal.trigger>
        @endcan
    </div>
</div>
<div class="mt-4 flex-1">
    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
        <li class="px-4 py-3 flex space-x-4">
            <p class="whitespace-no-wrap font-bold w-48">Gender</p>
            <p class="flex-1 text-gray-800">{{ $scholar->gender }}</p>
        </li>
        <li class="px-4 py-3 flex space-x-4">
            <h4 class="whitespace-no-wrap font-bold w-48">Email</h4>
            <p class="flex-1 text-gray-800"> {{ $scholar->email }}</p>
        </li>
        <li class="px-4 py-3 flex space-x-4">
            <p class="whitespace-no-wrap font-bold w-48">Contact Number</p>
            <p class="flex-1 text-gray-800">{{ $scholar->phone }}</p>
        </li>
        <li class="px-4 py-3 flex space-x-4">
            <p class="whitespace-no-wrap font-bold w-48">Address</p>
            <p class="flex-1 text-gray-800">
                {{ $scholar->address ?? 'Not Known'}}
            </p>
        </li>
    </ul>
</div>
@push('modals')
    <x-modal name="edit-scholar-basic-info-modal" class="p-6 w-1/2"
    :open="$errors->update->hasAny(['phone', 'address', 'gender'])">
    <h2 class="text-lg font-bold mb-8">Edit Basic Info - {{ $scholar->name }}</h2>
        @include('_partials.forms.edit-scholar-basic-info' ,[
            'genders' => App\Types\Gender::values(),
        ])
    </x-modal>
@endpush
<div>
    <div class="w-64 pr-4 relative -ml-8 my-6">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Basic Information
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <div class="mt-4">
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
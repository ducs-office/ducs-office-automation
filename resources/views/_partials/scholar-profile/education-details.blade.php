@push('modals')
    <x-modal name="edit-scholar-education-details-modal" class="p-6 w-auto"
    :open="$errors->update->hasAny(['education_details.*', 'education_details'])">
    <h2 class="text-lg font-bold mb-8">Edit Education Details - {{ $scholar->name }}</h2>
        @include('_partials.forms.edit-scholar-education-details', [
            'degrees' => $degrees,
            'subjects' => $subjects,
            'institutes' => $institutes,
        ])
    </x-modal>
@endpush
<div class="flex items-center">
    <h3 class="px-3 text-lg font-bold">
        Education Details
    </h3>
    <div class="ml-auto">
        @can('updateProfile', [App\Models\Scholar::class, $scholar])
        <x-modal.trigger modal="edit-scholar-education-details-modal"
            class="p-1 text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
            <x-feather-icon class="h-5" name="edit-3">Edit</x-feather-icon>
        </x-modal.trigger>
        @endcan
    </div>
</div>
<div class="flex-1 mt-4">
    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
        @forelse($scholar->education_details as $education)
        <li class="py-4 px-6">
            <p class="font-bold"> {{ $education->institute }} </p>
            <p class="text-gray-700 truncate">
                <span>{{ $education->degree }}</span>
                <span class="mx-1">&bullet;</span>
                <span>{{ $education->subject }}</span>
            </p>
            <p class="text-gray-700"> {{ $education->year }} </p>
        </li>
        @empty
        <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No education details
            added!</li>
        @endforelse
    </ul>
</div>
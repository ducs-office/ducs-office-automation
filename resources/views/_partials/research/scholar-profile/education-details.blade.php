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
<div>
    <div class="w-64 pr-4 relative -ml-8 my-6">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Education Details
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <div class="mt-4">
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
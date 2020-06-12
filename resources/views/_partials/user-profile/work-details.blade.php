@push('modals')
<x-modal name="edit-user-work-modal" class="p-6 w-1/2"
    :open="$errors->update->hasAny(['status', 'designation', 'affiliation', 'college_id'])">
    <h2 class="text-lg font-bold mb-3">{{ $user->name }}</h2>
    <h3 class="mb-6 font-bold">Work Details</h3>
    @include('_partials.forms.edit-user-work')
</x-modal>
@endpush
<div class="flex items-center">
    <h3 class="px-3 text-lg font-bold">
        Work Details
    </h3>
    @can('updateProfile', $user)
    <x-modal.trigger modal="edit-user-work-modal"  title="Edit"
        class="p-1 ml-auto text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
        <x-feather-icon name="edit" class="h-current mr-2"> Edit </x-feather-icon>
    </x-modal.trigger>
    @endcan
</div>
<div class="mt-4 flex-1">
    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
        @if($user->isCollegeTeacher() || $user->isFacultyTeacher())
        <li class="px-4 py-3 flex space-x-4">
            <p class="whitespace-no-wrap font-bold w-48">Status</p>
            <p class="flex-1 text-gray-800">{{ $user->status ?? '-' }}</p>
        </li>
        @endif
        <li class="px-4 py-3 flex space-x-4">
            <p class="whitespace-no-wrap font-bold w-48">Designation</p>
            <p class="flex-1 text-gray-800">{{ $user->designation ?? '-' }}</p>
        </li>
        @if ($user->isExternal())
        <li class="px-4 py-3 flex space-x-4">
            <p class="whitespace-no-wrap font-bold w-48">Affiliation</p>
            <p class="flex-1 text-gray-800">{{ $user->affiliation ?? '-' }}</p>
        </li>
        @else
        <li class="px-4 py-3 flex space-x-4">
            <p class="whitespace-no-wrap font-bold w-48">College/Department</p>
            <p class="flex-1 text-gray-800">{{ optional($user->college)->name ?? '-' }}</p>
        </li>
        @endif
    </ul>
</div>
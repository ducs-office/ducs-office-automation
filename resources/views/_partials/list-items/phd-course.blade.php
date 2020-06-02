<tr class="hover:bg-gray-100">
    <td class="table-fit px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-600">
        <span class="text-gray-800">
            {{ $course->code }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <h3>{{ $course->name }}</h3>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <span class="rounded-full text-sm px-3 py-1 bg-gray-200 text-gray-800 font-semibold">
            {{ $course->type }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 leading-5 font-medium">
        <div class="flex justify-end items-center space-x-1">
            @can('update', App\Models\PhdCourse::class)
            <x-modal.trigger :livewire="['payload' => $course->id]" modal="edit-phd-course-modal" title="Edit"
                class="p-1 text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
                <x-feather-icon class="h-5" name="edit-3">Edit</x-feather-icon>
            </x-modal.trigger>
            @endcan
            @can('delete', App\Models\PhdCourse::class)
            <form action="{{ route('staff.phd_courses.destroy', $course) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete course?');">
                @csrf_token
                @method('DELETE')
                <button type="submit" class="p-1 text-gray-700 font-bold hover:text-red-700 transition duration-300 transform hover:scale-110">
                    <x-feather-icon name="trash-2" class="h-5">Delete</x-feather-icon>
                </button>
            </form>
            @endcan
        </div>
    </td>
</tr>

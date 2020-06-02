<tr class="hover:bg-gray-100">
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
                <img class="h-10 w-10 bg-gray-400 rounded-full overflow-hidden"
                    src="#"
                    alt="{{ $scholar->name }}'s Avatar" />
            </div>
            <div class="ml-4">
                <div class="leading-5 font-bold text-gray-900">
                    <a class="hover:underline" href="{{ route('research.scholars.show', $scholar) }}">{{ $scholar->name }}</a>
                </div>
                <div class="leading-5 text-gray-600">{{ $scholar->email }}</div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-600"
        title="{{ $scholar->research_area }}">
        @if($scholar->research_area)
            {{ Str::limit($scholar->research_area, 30) }}
        @else
            <em>not set</em>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 space-y-1">
        @if($scholar->currentSupervisor)
            <div class="flex items-center leading-5 text-gray-900 space-x-2">
                <img class="h-8 w-8 bg-gray-400 rounded-full overflow-hidden flex-shrink-0"
                    src="{{ $scholar->currentSupervisor->getAvatarUrl() }}"
                    alt="{{ $scholar->currentSupervisor->name }}'s Avatar" />
                <div>
                    <h6>{{ $scholar->currentSupervisor->name }}</h6>
                    <x-modal.trigger class="inline-flex items-center space-x-1 link"
                        modal="replace-supervisor-modal"
                        :livewire="['payload' => $scholar->id]">
                        <x-feather-icon name="refresh-cw" class="h-current"></x-feather-icon>
                        <span>Replace</span>
                    </x-modal.trigger>
                </div>
            </div>
        @endif
    </td>
    <td class="px-6 py-4 leading-5 whitespace-no-wrap border-b border-gray-200">
        <div class="flex items-center text-gray-900 space-x-2">
            <img class="h-8 w-8 bg-gray-400 rounded-full overflow-hidden flex-shrink-0"
                src="{{ optional($scholar->currentCosupervisor)->getAvatarUrl() ?? '#' }}"
                alt="{{ $scholar->currentCosupervisor ? $scholar->currentCosupervisor->name . '\'s Avatar' : '' }}" />
            <div>
                @if($scholar->currentCosupervisor)
                    <h6>{{ $scholar->currentCosupervisor->name }}</h6>
                @else
                    <h6 class="text-gray-600 font-bold">None</h6>
                @endif
                <x-modal.trigger class="inline-flex items-center space-x-1 link" modal="replace-cosupervisor-modal"
                    :livewire="['payload' => $scholar->id]">
                    <x-feather-icon name="refresh-cw" class="h-current"></x-feather-icon>
                    <span>Replace</span>
                </x-modal.trigger>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 leading-5 font-medium">
        <div class="flex justify-end items-center space-x-1">
            @can('update', App\Models\Scholar::class)
            <x-modal.trigger :livewire="['payload' => $scholar->id]" modal="edit-scholar-modal"  title="Edit"
                class="p-1 text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
                <x-feather-icon class="h-5" name="edit-3">Edit</x-feather-icon>
            </x-modal.trigger>
            @endcan
            @can('delete', $scholar)
            <form action="{{ route('staff.scholars.destroy', $scholar) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete scholar \'{{ $scholar->name }}\'?');">
                @csrf_token @method('delete')
                <button type="submit" title="Delete" class="p-1 text-gray-700 font-bold hover:text-red-700 transition duration-300 transform hover:scale-110">
                    <x-feather-icon class="h-5" name="trash-2">Trash</x-feather-icon>
                </button>
            </form>
            @endcan
        </div>
    </td>
</tr>

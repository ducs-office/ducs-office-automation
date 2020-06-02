<tr class="hover:bg-gray-100">
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
                <img class="h-10 w-10 rounded-full overflow-hidden"
                    src="{{ $cosupervisor->getAvatarUrl() }}"
                    alt="{{ $cosupervisor->name }}'s Avatar" />
            </div>
            <div class="ml-4">
                <div class="leading-5 font-bold text-gray-900"><a class="hover:underline" href="{{ route('profiles.show', $cosupervisor) }}">{{ $cosupervisor->name }}</a></div>
                <div class="leading-5 text-gray-600">{{ $cosupervisor->email }}</div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-600">
        {{ $cosupervisor->category }}
    </td>
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <div class="leading-5 text-gray-900">
            {{ $cosupervisor->designation ?? 'Unknown' }}
            @if($cosupervisor->isSupervisor()) / Supervisor
            @elseif($cosupervisor->isCosupervisor()) / Cosupervisor
            @endif
        </div>
        <div class="max-w-24 truncate leading-5 text-gray-600"
            title="{{ $cosupervisor->affiliation }}">{{ Str::limit($cosupervisor->affiliation, 25) }}</div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 leading-5 font-medium">
        <div class="flex justify-end items-center space-x-1">
            <form action="{{ route('staff.cosupervisors.destroy', $cosupervisor) }}" method="POST" class="mb-0"
                onsubmit="return confirm('Do you really want to remove \'{{ $cosupervisor->name }}\' as cosupervisor?');">
                @csrf_token @method('delete')
                <button type="submit" title="Remove as Cosupervisor" class="p-1 text-gray-700 font-bold hover:text-red-700 transition duration-300 transform hover:scale-110">
                    <x-feather-icon class="h-5" name="x">Trash</x-feather-icon>
                </button>
            </form>
        </div>
    </td>
</tr>

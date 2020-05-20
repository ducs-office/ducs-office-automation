<tr class="hover:bg-gray-100">
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
                <img class="h-10 w-10 rounded-full overflow-hidden"
                    src="{{ $user->getAvatarUrl() }}"
                    alt="{{ $user->name }}'s Avatar" />
            </div>
            <div class="ml-4">
                <div class="leading-5 font-medium text-gray-900">{{ $user->name }}</div>
                <div class="leading-5 text-gray-600">{{ $user->email }}</div>
            </div>
        </div>
    </td>
    <td class="table-fit px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-600">
        {{ $user->category }}
    </td>
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <div class="leading-5 text-gray-900">
            {{ $user->designation ?? 'Unknown' }}
            @if($user->isSupervisor()) / Supervisor
            @elseif($user->isCosupervisor()) / Cosupervisor
            @endif
        </div>
        <div class="leading-5 text-gray-600">{{ ucwords($user->affiliation) }}</div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        @foreach ($user->roles as $role)
        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">
            {{ ucwords($role->name) }}
        </span>
        @endforeach
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 leading-5 font-medium">
        <div class="flex justify-end items-center space-x-3">
            @can('update', App\Models\User::class)
            <button type="submit" class="text-gray-700 font-bold hover:text-pink-800"
                x-on:click="$modal.showLivewire('edit-user-modal', 'show', {{ $user->id }} )">
                <x-feather-icon class="h-5" name="edit-3">Edit</x-feather-icon>
            </button>
            @endcan
            @can('delete', $user)
            <form action="{{ route('staff.users.destroy', $user) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete user \'{{ $user->name }}\'?');">
                @csrf_token @method('delete')
                <button type="submit" class="text-gray-700 font-bold hover:text-red-700">
                    <x-feather-icon class="h-5" name="trash-2">Trash</x-feather-icon>
                </button>
            </form>
            @endcan
            @if(! $user->isSupervisor())
            <x-dropdown>
                <x-dropdown-toggle>
                    <x-feather-icon name="more-vertical" class="h-5"></x-feather-icon>
                </x-dropdown-toggle>
                <x-dropdown-content class="mt-1 bg-white shadow-lg border rounded">
                    <ul>
                        <li>
                            <form action="{{ route('staff.users.update', $user) }}" method="POST"
                                onsubmit="return confirm('Caution: This action cannot be undone. Are you sure?');">
                                @csrf_token @method('PATCH')
                                <input type="hidden" name="is_supervisor" value="1">
                                <button class="px-4 py-2 hover:bg-gray-100 text-gray-700 font-bold hover:text-gray-900">Make Supervisor</button>
                            </form>
                        </li>
                    </ul>
                </x-dropdown-content>
            </x-dropdown>
            @endif
        </div>
    </td>
</tr>

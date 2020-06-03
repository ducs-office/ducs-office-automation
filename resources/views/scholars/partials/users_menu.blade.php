<div class="ml-auto mr-3" x-data="{ isOpen: false, }">
    <button class="relative z-20 flex items-center btn" x-on:click="isOpen = !isOpen">
        <img src=" {{ auth()->user()->getAvatarUrl() }}"
            alt="{{ Auth::user()->name }}" width="32" height="32" class="w-6 h-6 rounded-full mr-2">
        <h2 class="font-bold truncate max-w-32">{{ Auth::user()->first_name }}</h2>
    </button>
    <template x-if="isOpen">
        <div class="relative">
            <div class="fixed inset-0 bg-black-30"></div>
            <ul x-on:click.away="isOpen = false"
                class="absolute mt-4 right-0 z-50 min-w-48 max-w-xs p-4 bg-white rounded shadow-lg space-y-2">
                <li>
                    <a href="{{ route('scholars.profile.show', auth()->user()) }}" class="w-full inline-flex items-center btn border-0 bg-transparent hover:bg-gray-200">
                        <x-feather-icon name="user" class="h-current" stroke-width="2">Change Password Icon</x-feather-icon>
                        <span class="ml-2 whitespace-no-wrap">Profile</span>
                    </a>
                </li>
                <li>
                    <button class="w-full inline-flex items-center btn border-0 bg-transparent hover:bg-gray-200" x-on:click="$modal.show('change-password-modal'); console.log($modal.current)">
                        <x-feather-icon name="key" class="h-current" stroke-width="2">Change Password Icon</x-feather-icon>
                        <span class="ml-2 whitespace-no-wrap">Change Password</span>
                    </button>
                </li>
                <li>
                    <button type="submit" form="logout-form"
                        formaction="{{ route('logout', ['scholar']) }}"
                        formmethod="POST"
                        class="w-full inline-flex items-center btn border-0 bg-transparent hover:bg-red-700 hover:text-white">
                        <x-feather-icon name="power" class="h-current" stroke-width="2">Logout Icon</x-feather-icon>
                        <span class="ml-2 whitespace-no-wrap">Logout</span>
                    </button>
                </li>
            </ul>
        </div>
    </template>
    <x-modal name="change-password-modal" class="p-6">
        <h2 class="text-lg font-bold mb-6">Change Password</h2>
        <form action="{{ route('staff.account.change_password') }}" method="POST">
            @csrf_token
            <div class="mb-3">
                <label for="current_password" class="w-full form-label mb-1">Current Password</label>
                <input type="password" name="password" id="current_password" class="w-full form-input">
            </div>
            <div class="mb-3">
                <label for="new_password" class="w-full form-label mb-1">New Password</label>
                <input type="password" name="new_password" id="new_password" class="w-full form-input">
            </div>
            <div class="mb-3">
                <label for="new_password_confirmation" class="w-full form-label mb-1">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                    class="w-full form-input">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-magenta">Change Password</button>
            </div>
        </form>
    </x-modal>
</div>
<form id="logout-form" class="h-0 w-0 pointer-events-none">
    @csrf_token
</form>

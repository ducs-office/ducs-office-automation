<div class="ml-auto mr-3 relative">
    <button class="relative z-20 flex items-center btn pl-2 pr-6 py-1" x-on:click="userDropdown = !userDropdown">
        <img src="{{ Auth::user()->avatar_url }}"
            alt="{{ Auth::user()->name }}" width="32" height="32"
            class="w-6 h-6 rounded-full mr-2">
        <div class="leading-none text-left">
            <h2 class="font-bold truncate max-w-32">{{ auth()->user()->first_name }}</h2>
            <h3 class="text-xs text-black-50">{{ auth()->user()->category ?? 'Unknown' }}</h3>
        </div>
    </button>
    <ul x-show="userDropdown" x-on:click.away="userDropdown = false"
        class="absolute mt-4 right-0 z-50 min-w-48 max-w-xs p-4 bg-white rounded shadow-lg space-y-2"
        x-transition:enter="transition ease-in duration-150"
        x-transition:enter-start="transform origin-top-right scale-0 opacity-0"
        x-transition:enter-end="transform origin-top-right scale-100 opacity-100"
        x-transition:leave="transition ease-out duration-150"
        x-transition:leave-start="transform origin-top-right scale-100 opacity-100"
        x-transition:leave-end="transform origin-top-right scale-0 opacity-0">
        <li>
            <a href="{{ route('profiles.show', auth()->user()) }}" class="w-full inline-flex items-center btn border-0 bg-transparent hover:bg-gray-200">
                <x-feather-icon name="user" class="h-current" stroke-width="2">Change Password Icon</x-feather-icon>
                <span class="ml-2 whitespace-no-wrap">Profile</span>
            </a>
        </li>
        <li>
            <x-modal.trigger modal="change-password-modal" class="w-full inline-flex items-center btn border-0 bg-transparent hover:bg-gray-200">
                <x-feather-icon name="key" class="h-current" stroke-width="2">Change Password Icon</x-feather-icon>
                <span class="ml-2 whitespace-no-wrap">Change Password</span>
            </x-modal.trigger>
        </li>
        <li>
            <button type="submit" form="logout-form"
                formaction="{{ route('logout') }}"
                formmethod="POST"
                class="w-full inline-flex items-center btn border-0 bg-transparent hover:bg-red-700 hover:text-white">
                <x-feather-icon name="power" class="h-current" stroke-width="2">Logout Icon</x-feather-icon>
                <span class="ml-2 whitespace-no-wrap">Logout</span>
            </button>
        </li>
    </ul>
</div>
<form id="logout-form" class="h-0 w-0 pointer-events-none">
    @csrf_token
    <input type="hidden" name="type" value="scholars">
</form>
@push('modals')
<x-modal name="change-password-modal" class="p-6">
    <h2 class="text-lg font-bold mb-6">Change Password</h2>
    <form action="{{ route('staff.account.change_password') }}" method="POST">
        @csrf_token
        <div class="mb-3">
            <label for="current_password" class="w-full form-label mb-1">Current Password</label>
            <x-input.password name="password" id="current_password" class="w-full form-input"></x-input.password>
        </div>
        <div class="mb-3">
            <label for="new_password" class="w-full form-label mb-1">New Password</label>
            <x-input.password name="new_password" id="new_password" class="w-full form-input"></x-input.password>
        </div>
        <div class="mb-3">
            <label for="new_password_confirmation" class="w-full form-label mb-1">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full form-input">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-magenta">Change Password</button>
        </div>
    </form>
</x-modal>
@endpush

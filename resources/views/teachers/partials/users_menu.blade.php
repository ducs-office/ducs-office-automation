<toggle-visibility class="relative ml-auto mr-3">
    <template v-slot="userMenu">
        <button class="flex items-center btn" @click.stop="userMenu.toggle">
            <img src="https://gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?s=48&d=identicon"
                alt="{{ Auth::user()->name }}" width="32" height="32" class="w-6 h-6 rounded-full mr-2">
            <h2 class="font-bold truncate max-w-32">{{ Auth::user()->first_name }}</h2>
        </button>
        <ul v-if="userMenu.isVisible" v-click-outside="userMenu.hide"
            class="absolute mt-2 right-0 z-50 min-w-48 max-w-xs p-4 bg-white border rounded shadow-lg">
            <li class="mb-2">
                <button class="w-full inline-flex items-center btn" @click="$modal.show('change_password_modal')">
                    <feather-icon name="key" class="h-current" stroke-width="3">Change Password Icon</feather-icon>
                    <span class="ml-2 whitespace-no-wrap">Change Password</span>
                </button>
            </li>
            <li class="">
                <button type="submit" form="logout-form"
                    formaction="{{ route('logout') }}"
                    formmethod="POST"
                    class="w-full inline-flex items-center btn btn-red">
                    <feather-icon name="power" class="h-current" stroke-width="3">Logout Icon</feather-icon>
                    <span class="ml-2 whitespace-no-wrap">Logout</span>
                </button>
            </li>
        </div>

    </template>
</toggle-visibility>
<form id="logout-form" class="h-0 w-0 pointer-events-none">
    @csrf_token
    <input type="hidden" name="type" value="teachers">
</form>
<v-modal name="change_password_modal" height="auto">
    <div class="p-6">
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
    </div>
</v-modal>

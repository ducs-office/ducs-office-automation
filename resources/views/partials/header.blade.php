<header class="bg-white text-gray-900 py-2 h-16 px-4 flex items-center w-full flex-shrink-0">
    @auth
    <sidebar-nav-button class="md:hidden p-3 text-gray-700 mr-3 btn flex-shrink-0">
        <feather-icon name="menu" class="h-current" stroke-width="3">Toggle Menu</feather-icon>
    </sidebar-nav-button>
    <form action="" method="GET" class="relative flex-1 mr-4">
        <input type="text" class="w-full form-input pl-8" placeholder="Search..." >
        <feather-icon name="search" class="absolute left-0 ml-2 absolute-y-center text-gray-600 h-5"></feather-icon>
    </form>
    <button class="flex items-center mr-3 btn" @click="$modal.show('change_password_modal')">
        <img src="https://gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?s=48&d=identicon"
            alt="{{ Auth::user()->name }}" width="32" height="32" class="w-6 h-6 rounded-full mr-2">
        <h2 class="font-bold truncate max-w-32">{{ head(explode(' ', Auth::user()->name)) }}</h2>
    </button>
    <modal name="change_password_modal" height="auto">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-6">Change Password</h2>
            <form action="{{ route('account.change_password') }}" method="POST">
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
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full form-input">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-magenta">Change Password</button>
                </div>
            </form>
        </div>
    </modal>
    <form action="{{ route('logout') }}" method="POST" class="inline-flex items-center">
        @csrf_token
        <button type="submit" class="p-3 text-red-700 hover:bg-gray-100 rounded">
            <feather-icon name="power" class="h-current" stroke-width="3">Logout Button</feather-icon>
        </button>
    </form>
    @endauth
    @guest
    <a class="bg-white text-gray-900 px-3 py-1 rounded font-bold" href="{{ route('login') }}">Login</a>
    @endguest
</header>

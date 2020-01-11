<header class="bg-white text-gray-900 py-2 h-16 px-4 flex items-center w-full flex-shrink-0">
    @auth
    <button class="p-3 text-gray-700 mr-3 btn flex-shrink-0" @click="sidebar.toggle">
        <feather-icon :name="sidebar.isVisible ? 'arrow-left' : 'menu'" class="h-current" stroke-width="3">
            Toggle Menu
        </feather-icon>
    </button>
    <form action="" method="GET" class="relative flex-1 mr-4">
        <input type="text" class="w-full form-input pl-8" placeholder="Search..." >
        <feather-icon name="search" class="absolute left-0 ml-2 absolute-y-center text-gray-600 h-5"></feather-icon>
    </form>
    @include('partials.users_menu')
    @endauth
    @guest
    <a class="bg-white text-gray-900 px-3 py-1 rounded font-bold" href="{{ route('login') }}">Login</a>
    @endguest
</header>

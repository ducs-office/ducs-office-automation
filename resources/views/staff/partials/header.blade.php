<header class="bg-white text-gray-900 py-2 h-16 px-4 flex items-center w-full flex-shrink-0 space-x-2">
    @auth
        <button id="sidebar-toggle" role="button" aria-controls="sidebar" class="p-3 text-gray-700 mr-3 btn flex-shrink-0" x-on:click="sidebar = !sidebar">
            <template x-if="sidebar">
                <x-feather-icon name="arrow-left" class="h-current" stroke-width="3">
                    Hide Sidebar
                </x-feather-icon>
            </template>
            <template x-if="! sidebar">
                <x-feather-icon name="menu" class="h-current" stroke-width="3">
                    Show Sidebar
                </x-feather-icon>
            </template>
        </button>
        <form action="" method="GET" class="relative flex-1 mr-4">
            <input type="text" class="w-full form-input pl-8" placeholder="Search..." >
            <x-feather-icon name="search" class="absolute left-0 ml-2 absolute-y-center text-gray-600 h-5"></x-feather-icon>
        </form>
        @include('_partials.notifications')
        @include('staff.partials.users_menu')
    @else
        <a class="bg-white text-gray-900 px-3 py-1 rounded font-bold" href="{{ route('login') }}">Login</a>
    @endauth
</header>

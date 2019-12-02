<header class="bg-white text-gray-900 py-2 h-16 px-4 flex justify-end items-center">
    @auth
    <form action="" method="GET" class="relative flex-1 mr-4">
        <input type="text" class="w-full form-input pl-8" placeholder="Search..." >
        <feather-icon name="search" class="absolute left-0 ml-2 absolute-y-center text-gray-600 h-5"></feather-icon>
    </form>
    <button class="flex items-center mr-3 btn">
        <img src="https://gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?s=48&d=identicon"
            alt="{{ Auth::user()->name }}" width="32" height="32" class="w-6 h-6 rounded-full mr-2">
        <h2 class="font-bold truncate max-w-32">{{ head(explode(' ', Auth::user()->name)) }}</h2>
    </button>
    <form action="{{ route('logout') }}" method="POST" class="inline-flex items-center">
        @csrf
        <button type="submit" class="p-3 text-red-700 hover:bg-gray-100 rounded">
            <feather-icon name="power" class="h-current" stroke-width="3">Logout Button</feather-icon>
        </button>
    </form>
    @endauth
    @guest
    <a class="bg-white text-gray-900 px-3 py-1 rounded font-bold" href="{{ route('login') }}">Login</a>
    @endguest
</header>

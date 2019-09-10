<header class="bg-white text-gray-900 shadow px-4 flex items-center justify-between">
    <div class="mr-4">
        <a href="/" class="inline-block logo py-2 leading-none max-w-sm">
            <h1 class="text-sm md:text-lg font-bold">Department of Computer Science</h1>
            <h2 class="text-xs font-bold tracking-widest">University of Delhi</h2>
        </a>
    </div>
    <div class="flex items-center">
        @auth
        <button class="flex items-center mr-3 px-2 py-2 hover:bg-black-10">
            <img src="https://gravatar.com/avatar/{{ md5(trim(Auth::user()->email)) }}" alt="{{ Auth::user()->name }}"
                class="w-6 h-6 rounded-full mr-2">
            <h2 class="font-bold truncate max-w-xs">{{ Auth::user()->name }}</h2>
        </button>
        <form action="\logout" method="POST">
            @csrf
            <button type="submit" class="text-gray-900 hover:text-red-500 p-2 rounded font-bold">
                <feather-icon name="power" class="h-current" stroke-width="3">Logout Button</feather-icon>
            </button>
        </form>
        @endauth
        @guest
        <a class="bg-white text-gray-900 px-3 py-1 rounded font-bold" href="/login">Login</a>
        @endguest
    </div>
</header>
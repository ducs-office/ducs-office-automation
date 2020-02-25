<header class="bg-white text-gray-900 py-2">
    <div class="container px-4 mx-auto flex items-center">
        <div class="flex items-center px-4">
            <img src="{{ asset('images/university-logo.png')}}" alt="DU Logo" class="h-12 mr-3">
            <a href="{{ route('scholars.profile') }}" class="inline-block logo leading-tight max-w-screen mr-4">
                <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
            </a>
        </div>
        @auth('scholars')
        @include('scholars.partials.users_menu')
        @endauth
        @guest('scholars')
        <a class="bg-white text-gray-900 px-3 py-1 rounded font-bold" href="{{ route('login') }}">Login</a>
        @endguest
    </div>
</header>
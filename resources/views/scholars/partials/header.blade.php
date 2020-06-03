<header class="sticky top-0 z-10 bg-white text-gray-900 py-2 shadow-lg">
    <div class="container px-8 mx-auto flex items-center">
        <h4 class="text-xl font-semibold">{{ $pageTitle ?? '' }}</h4>
        @auth
        @include('scholars.partials.users_menu')
        @endauth
        @guest
        <a class="bg-white text-gray-900 px-3 py-1 rounded font-bold" href="{{ route('login') }}">Login</a>
        @endguest
    </div>
</header>

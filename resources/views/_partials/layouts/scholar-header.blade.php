<header class="sticky top-0 z-10 shadow-lg">
    <div class="container px-8 mx-auto flex items-center bg-white text-gray-900 py-2">
        <h4 class="text-xl font-semibold">{{ $pageTitle ?? '' }}</h4>
        @auth
        @include('_partials.layouts.scholar-menu')
        @endauth
        @guest
        <a class="bg-white text-gray-900 px-3 py-1 rounded font-bold" href="{{ route('login') }}">Login</a>
        @endguest
    </div>
    @auth('web')
    <div class="container mx-auto bg-gray-100 py-1"> 
        <p class="text-right text-gray-900"> Click 
            <a href="{{ route('staff.dashboard') }}" class="link"> here </a> 
        to go back to your dashboard.</p>
    </div>
    @endauth
</header>

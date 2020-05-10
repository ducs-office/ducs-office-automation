<header class="bg-white text-gray-900 py-2">
    <div class="container px-4 mx-auto flex items-center">
        <div class="flex items-center px-4">
            <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
            <a href="{{ route('teachers.profile') }}" class="inline-block logo leading-tight max-w-sm mr-4">
                <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
            </a>
        </div>
        @auth
            <ul class="self-end -mb-2 flex">
                @if(auth()->user()->isSupervisor())
                <li><a class="inline-block font-bold pt-4 pb-3 px-4 mx-3 rounded-t border-b-4 border-transparent hover:border-magenta-600 hover:bg-gray-200" href="{{ route('research.scholars.index') }}">Manage Scholars</a></li>
                <li><a class="inline-block font-bold pt-4 pb-3 px-4 mx-3 rounded-t border-b-4 border-transparent hover:border-magenta-600 hover:bg-gray-200" href="{{ route('research.publications.index') }}">Publications</a></li>
                @endif
            </ul>
        @include('teachers.partials.users_menu')
        @include('teachers.partials.notifications')
        @endauth
        @guest('web')
        <a class="bg-white text-gray-900 px-3 py-1 rounded font-bold" href="{{ route('login') }}">Login</a>
        @endguest
    </div>
</header>

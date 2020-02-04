<div class="h-20 flex items-center px-4 mb-8">
    <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
    <a href="{{ route('teachers.dashboard') }}" class="inline-block logo leading-tight max-w-sm mr-4">
        <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
    </a>
</div>
<aside class="flex-1 px-4">
    <ul class="font-bold text-white-90">
        <li class="mb-1 last:mb-0">
            <a href="{{ route('teachers.dashboard') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="grid" class="h-4 mr-2" stroke-width="2">Dashboard</feather-icon>
                Dashboard
            </a>
        </li>
    </ul>
</aside>

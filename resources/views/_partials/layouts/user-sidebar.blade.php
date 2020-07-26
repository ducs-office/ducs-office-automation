<div class="h-20 flex items-center px-4 py-6">
    <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
    <a href="{{ route('staff.dashboard') }}" class="inline-block logo leading-tight max-w-sm mr-4">
        <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
    </a>
</div>
<nav class="flex-1 px-4 py-4 space-y-6">
    <ul class="font-bold text-white-90 space-y-1">
        <li>
            <a href="{{ route('staff.dashboard') }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="grid" class="h-4 mr-2" stroke-width="2">Dashboard</x-feather-icon>
                Dashboard
            </a>
        </li>
        @canany(['viewAny', 'create'], App\Models\TeachingDetail::class)
        <li>
            <a href="{{ route('teaching-details.index') }}"
                class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="database" class="h-4 mr-2">My Teaching Details</x-feather-icon>
                My Teaching Details
            </a>
        </li>
        @endcan
        {{-- @can('viewAny', App\Models\Scholar::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.scholars.index') }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="users" class="h-4 mr-2">Scholar</x-feather-icon>
                My Scholars
            </a>
        </li>
        @endcan --}}
        @can('viewAny', App\Models\Publication::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('users.publications.index', auth()->user()) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="file-text" class="h-4 mr-2">Publications</x-feather-icon>
                My Publications
            </a>
        </li>
        @endcan
        <li class="mb-1 last:mb-0">
            <a href="{{ route('profiles.show', auth()->user()) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="user" class="h-4 mr-2">Profile</x-feather-icon>
                Profile
            </a>
        </li>
    </ul>

    @if(
        Auth::user()->can('viewAny', \App\Models\OutgoingLetter::class) ||
        Auth::user()->can('viewAny', App\Models\IncomingLetter::class) ||
        Auth::user()->can('viewAny', App\Models\Programme::class) ||
        Auth::user()->can('viewAny', App\Models\Course::class) ||
        Auth::user()->can('viewAny', App\Models\College::class) ||
        Auth::user()->can('viewAny', App\Models\TeachingRecord::class) ||
        Auth::user()->can('start', App\Models\TeachingRecord::class) ||
        Auth::user()->can('extend', App\Models\TeachingRecord::class)
    )
    <div class="space-y-2">
        <h6 class="px-4 text-sm uppercase tracking-wider font-bold text-white-50">Resource Management</h6>
        <ul class="font-bold text-white-90 space-y-1">
            @can('viewAny', \App\Models\OutgoingLetter::class)
            <li>
                <a href="{{ route('staff.outgoing_letters.index') }}"
                    class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="mail" class="h-4 mr-2">Outgoing Letters</x-feather-icon>
                    Outgoing Letters
                </a>
            </li>
            @endcan
            @can('viewAny', App\Models\IncomingLetter::class)
            <li>
                <a href="{{ route('staff.incoming_letters.index') }}"
                    class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="mail" class="h-4 mr-2">Incoming Letters</x-feather-icon>
                    Incoming Letters
                </a>
            </li>
            @endcan
            @can('viewAny', App\Models\Programme::class)
            <li>
                <a href="{{ route('staff.programmes.index') }}"
                    class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="book" class="h-4 mr-2">Academic Programmes</x-feather-icon>
                    Programmes
                </a>
            </li>
            @endcan
            @can('viewAny', App\Models\Course::class)
            <li>
                <a href="{{ route('staff.courses.index') }}"
                    class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="file-text" class="h-4 mr-2">Programme Courses</x-feather-icon>
                    Courses
                </a>
            </li>
            <li>
                <a href="{{ route('staff.phd_courses.index') }}"
                    class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="file-text" class="h-4 mr-2">Pre-PhD Courses</x-feather-icon>
                    Pre-PhD Courses
                </a>
            </li>
            @endcan
            @can('viewAny', App\Models\College::class)
            <li>
                <a href="{{ route('staff.colleges.index') }}"
                    class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="home" class="h-4 mr-2">Colleges</x-feather-icon>
                    Colleges
                </a>
            </li>
            @endcan
            @canany(['viewAny', 'start', 'extend'], App\Models\TeachingRecord::class)
            <li>
                <a href="{{ route('teaching-records.index') }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="database" class="h-4 mr-2">UG Teaching Records</x-feather-icon>
                    UG Teaching Records
                </a>
            </li>
            @endcanany
        </ul>
    </div>
    @endif

    @if(
        Auth::user()->can('viewAny', Spatie\Permission\Models\Role::class) ||
        Auth::user()->can('viewAny', App\Models\User::class) ||
        Auth::user()->can('viewAny', App\Models\Scholar::class)
    )
    <div class="space-y-2">
        <h6 class="px-4 text-sm uppercase tracking-wider font-bold text-white-50">Access Control</h6>
        <ul class="font-bold text-white-90 space-y-1">
            @can('viewAny', Spatie\Permission\Models\Role::class)
            <li>
                <a href="{{ route('staff.roles.index') }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="key" class="h-4 mr-2">User</x-feather-icon>
                    Roles & Permissions
                </a>
            </li>
            @endcan
            @can('viewAny', App\Models\User::class)
            <li>
                <a href="{{ route('staff.users.index') }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="user" class="h-4 mr-2">User</x-feather-icon>
                    Users
                </a>
            </li>
            @endcan
            @can('viewAny', App\Models\Scholar::class)
            <li>
                <a href="{{ route('staff.scholars.index') }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                    <x-feather-icon name="users" class="h-4 mr-2">Scholar</x-feather-icon>
                    Scholars
                </a>
            </li>
            @endcan
        </ul>
    </div>
    @endif
</nav>

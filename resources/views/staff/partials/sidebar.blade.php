<div class="h-20 flex items-center px-4 py-6">
    <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
    <a href="{{ route('staff.dashboard') }}" class="inline-block logo leading-tight max-w-sm mr-4">
        <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
    </a>
</div>
<nav class="flex-1 px-4 py-6">
    <ul class="font-bold text-white-80">
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.dashboard') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="grid" class="h-4 mr-2" stroke-width="2">Dashboard</x-feather-icon>
                Dashboard
            </a>
        </li>
        @can('viewAny', \App\Models\OutgoingLetter::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.outgoing_letters.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="mail" class="h-4 mr-2">Outgoing Letters</x-feather-icon>
                Outgoing Letters
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\IncomingLetter::class)
            <li class="mb-1 last:mb-0">
                <a href="{{ route('staff.incoming_letters.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                    <x-feather-icon name="mail" class="h-4 mr-2">Incoming Letters</x-feather-icon>
                    Incoming Letters
                </a>
            </li>
        @endcan
        @can('viewAny', App\Models\Programme::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.programmes.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="book" class="h-4 mr-2">Academic Programmes</x-feather-icon>
                Programmes
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\Course::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.courses.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="file-text" class="h-4 mr-2">Programme Courses</x-feather-icon>
                Courses
            </a>
        </li>
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.phd_courses.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="file-text" class="h-4 mr-2">Pre-PhD Courses</x-feather-icon>
                Pre-PhD Courses
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\College::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.colleges.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="home" class="h-4 mr-2">Colleges</x-feather-icon>
                Colleges
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\User::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.users.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="user" class="h-4 mr-2">User</x-feather-icon>
                Users
            </a>
        </li>
        @endcan
        @can('viewAny', Spatie\Permission\Models\Role::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.roles.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="key" class="h-4 mr-2">User</x-feather-icon>
                Roles & Permissions
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\TeachingRecord::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('teaching_records.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="database" class="h-4 mr-2">UG Teaching Records</x-feather-icon>
                UG Teaching Records
            </a>
        </li>
        @endcan
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.cosupervisors.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="users" class="h-4 mr-2">Co-supervisors</x-feather-icon>
                Co-Supervisors
            </a>
        </li>
        @can('viewAny', App\Models\Scholar::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.scholars.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="users" class="h-4 mr-2">Scholar</x-feather-icon>
                Scholar Logins
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\Scholar::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('research.scholars.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:text-white rounded">
                <x-feather-icon name="users" class="h-4 mr-2">Scholar</x-feather-icon>
                Research Scholars
            </a>
        </li>
        @endcan
        @can('viewAny', App\Publication::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('research.publications.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <x-feather-icon name="file-text" class="h-4 mr-2">Publications</x-feather-icon>
                Publications
            </a>
        </li>
        @endcan
    </ul>
</nav>

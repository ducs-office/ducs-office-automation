<div class="h-20 flex items-center px-4 mb-8">
    <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
    <a href="{{ route('staff.dashboard') }}" class="inline-block logo leading-tight max-w-sm mr-4">
        <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
    </a>
</div>
<aside class="flex-1 px-4">
    <ul class="font-bold text-white-90">
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.dashboard') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="grid" class="h-4 mr-2" stroke-width="2">Dashboard</feather-icon>
                Dashboard
            </a>
        </li>
        @can('viewAny', \App\OutgoingLetter::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.outgoing_letters.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="mail" class="h-4 mr-2">Outgoing Letters</feather-icon>
                Outgoing Letters
            </a>
        </li>
        @endcan
        @can('viewAny', App\IncomingLetter::class)
            <li class="mb-1 last:mb-0">
                <a href="{{ route('staff.incoming_letters.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                    <feather-icon name="mail" class="h-4 mr-2">Incoming Letters</feather-icon>
                    Incoming Letters
                </a>
            </li>
        @endcan
        @can('viewAny', App\Programme::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.programmes.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="book" class="h-4 mr-2">Academic Programmes</feather-icon>
                Programmes
            </a>
        </li>
        @endcan
        @can('viewAny', App\Course::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.courses.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="file-text" class="h-4 mr-2">Programme Courses</feather-icon>
                Courses
            </a>
        </li>
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.phd_courses.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="file-text" class="h-4 mr-2">Pre-PhD Courses</feather-icon>
                PhD Courses
            </a>
        </li>
        @endcan
        @can('viewAny', App\College::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.colleges.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="home" class="h-4 mr-2">Colleges</feather-icon>
                Colleges
            </a>
        </li>
        @endcan
        @can('viewAny', App\User::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.users.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="user" class="h-4 mr-2">User</feather-icon>
                Users
            </a>
        </li>
        @endcan
        @can('viewAny', Spatie\Permission\Models\Role::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.roles.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="key" class="h-4 mr-2">User</feather-icon>
                Roles & Permissions
            </a>
        </li>
        @endcan
        @can('viewAny', App\Teacher::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.teachers.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="users" class="h-4 mr-2">College Teacher</feather-icon>
                College Teachers
            </a>
        </li>
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.teaching_records.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="database" class="h-4 mr-2">UG Teaching Records</feather-icon>
                UG Teaching Records
            </a>
        </li>
        @endcan
        @can('viewAny', App\Scholar::class)
        <li class="mb-1 last:mb-0">
            <a href="{{ route('staff.scholars.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 rounded">
                <feather-icon name="users" class="h-4 mr-2">Scholar</feather-icon>
                Scholars
            </a>
        </li>
        @endcan
    </ul>
</aside>

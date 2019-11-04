<div class="bg-magenta-800 text-white w-80 flex flex-col flex-shrink-0">
    <div class="h-20 flex items-center px-4 mb-8">
        <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
        <a href="/" class="inline-block logo leading-tight max-w-sm">
            <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
        </a>
    </div>
    <aside class="flex-1">
        <ul class="font-bold text-white-90">
            <li>
                <a href="/" class="flex items-center py-2 px-4 hover:bg-magenta-700">
                    <feather-icon name="grid" class="h-4 mr-2" stroke-width="2">Dashboard</feather-icon>
                    Dashboard
                </a>
            </li>
            @canany('viewAny', \App\OutgoingLetter::class)
            <li>
                <a href="/outgoing-letters" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                    <feather-icon name="mail" class="h-4 mr-2">Outgoing Letters</feather-icon>
                    Outgoing Letters
                </a>
            </li>
            @endcanany
            @can('viewAny', App\Programme::class)
            <li>
                <a href="/programmes" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                    <feather-icon name="book" class="h-4 mr-2">Academic Programmes</feather-icon>
                    Academic Programmes
                </a>
            </li>
            @endcan
            @can('viewAny', App\Course::class)
            <li>
                <a href="/courses" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                    <feather-icon name="file-text" class="h-4 mr-2">Programme Courses</feather-icon>
                    Programme Courses
                </a>
            </li>
            @endcan
            @can('viewAny', App\College::class)
            <li>
                <a href="/colleges" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                    <feather-icon name="home" class="h-4 mr-2">Colleges</feather-icon>
                    Colleges
                </a>
            </li>
            @endcan
            @can('viewAny', App\User::class)
            <li>
                <a href="/users" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                    <feather-icon name="user" class="h-4 mr-2">User</feather-icon>
                    Users
                </a>
            </li>
            @endcan
            @can('viewAny', Spatie\Permission\Models\Role::class)
            <li>
                <a href="/roles" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                    <feather-icon name="key" class="h-4 mr-2">User</feather-icon>
                    Roles & Permissions
                </a>
            </li>
            @endcan
        </ul>
    </aside>
</div>

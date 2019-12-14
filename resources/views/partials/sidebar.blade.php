<sidebar-nav inline-template>
    <transition enter-class="translate-x-back-100"
        leave-to-class="translate-x-back-100"
        enter-active-class="transition-transform"
        leave-active-class="transition-transform">
        <div v-if="isVisible" class="bg-magenta-800 text-white w-80 flex flex-col flex-shrink-0">
            <div class="h-20 flex items-center px-4 mb-8">
                <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
                <a href="{{ route('dashboard') }}" class="inline-block logo leading-tight max-w-sm mr-4">
                    <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
                </a>
            </div>
            <aside class="flex-1">
                <ul class="font-bold text-white-90">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700">
                            <feather-icon name="grid" class="h-4 mr-2" stroke-width="2">Dashboard</feather-icon>
                            Dashboard
                        </a>
                    </li>
                    @can('viewAny', \App\OutgoingLetter::class)
                    <li>
                        <a href="{{ route('outgoing_letters.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="mail" class="h-4 mr-2">Outgoing Letters</feather-icon>
                            Outgoing Letters
                        </a>
                    </li>
                    @endcan
                    @can('viewAny', App\IncomingLetter::class)
                        <li>
                            <a href="{{ route('incoming_letters.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                                <feather-icon name="mail" class="h-4 mr-2">Incoming Letters</feather-icon>
                                Incoming Letters
                            </a>
                        </li>
                    @endcan
                    @can('viewAny', App\Programme::class)
                    <li>
                        <a href="{{ route('programmes.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="book" class="h-4 mr-2">Academic Programmes</feather-icon>
                            Academic Programmes
                        </a>
                    </li>
                    @endcan
                    @can('viewAny', App\Course::class)
                    <li>
                        <a href="{{ route('courses.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="file-text" class="h-4 mr-2">Programme Courses</feather-icon>
                            Programme Courses
                        </a>
                    </li>
                    @endcan
                    @can('viewAny', App\College::class)
                    <li>
                        <a href="{{ route('colleges.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="home" class="h-4 mr-2">Colleges</feather-icon>
                            Colleges
                        </a>
                    </li>
                    @endcan
                    @can('viewAny', App\User::class)
                    <li>
                        <a href="{{ route('users.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="user" class="h-4 mr-2">User</feather-icon>
                            Users
                        </a>
                    </li>
                    @endcan
                    @can('viewAny', Spatie\Permission\Models\Role::class)
                    <li>
                        <a href="{{ route('roles.index') }}" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="key" class="h-4 mr-2">User</feather-icon>
                            Roles & Permissions
                        </a>
                    </li>
                    @endcan
                </ul>
            </aside>
        </div>
    </transition>
</sidebar-nav>

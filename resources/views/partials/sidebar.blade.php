<sidebar-nav>
    <template v-slot:default="data">
        <div v-if="data.isvisible" class="bg-magenta-800 text-white sm:w-80 flex flex-col flex-shrink-0 h-full absolute sm:static z-50">
            <div class="h-20 flex items-center px-4 mb-8">
                <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
                <a href="/" class="inline-block logo leading-tight max-w-sm">
                    <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
                </a>
                <div class="self-center flex-grow text-right">
                    <button class="sm:hidden p-3 bg-magenta-800 hover:bg-magenta-900 text-white btn ml-3" @click="data.closesidebarnav">
                        <feather-icon name="close" class="h-current" stroke-width="3">Close Menu</feather-icon>
                    </button>
                </div>
            </div>
            <aside class="flex-1">
                <ul class="font-bold text-white-90">
                    <li>
                        <a href="/" class="flex items-center py-2 px-4 hover:bg-magenta-700">
                            <feather-icon name="grid" class="h-4 mr-2" stroke-width="2">Dashboard</feather-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="/outgoing-letters" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="mail" class="h-4 mr-2">Outgoing Letters</feather-icon>
                            Outgoing Letters
                        </a>
                    </li>
                    <li>
                        <a href="/courses" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="book" class="h-4 mr-2">Academic Courses</feather-icon>
                            Academic Courses
                        </a>
                    </li>
                    <li>
                        <a href="/papers" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="file-text" class="h-4 mr-2">Course Papers</feather-icon>
                            Course Papers
                        </a>
                    </li>
                    <li>
                        <a href="/colleges" class="flex items-center py-2 px-4 hover:bg-magenta-700 hover:pl-6">
                            <feather-icon name="home" class="h-4 mr-2">Colleges</feather-icon>
                            Colleges
                        </a>
                    </li>
                </ul>
            </aside>
        </div>
    </template>
</sidebar-nav>
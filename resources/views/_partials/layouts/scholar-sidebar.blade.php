<div class="h-20 flex items-center px-4 mb-8">
    <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
    <a href="{{ route('scholars.dashboard') }}" class="inline-block logo leading-tight max-w-sm mr-4">
        <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
    </a>
</div>
<aside class="flex-1 px-4">
    <ul class="font-bold text-white-90 space-y-2">
        <li>
            <a href="{{ route('scholars.dashboard') }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="grid" class="h-5 mr-4" stroke-width="2">Dashboard</x-feather-icon>
                Dashboard
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="book" class="h-5 mr-4" stroke-width="2">Coursework</x-feather-icon>
                Pre-PhD Courseworks
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="file" class="h-5 mr-4" stroke-width="2">Publications</x-feather-icon>
                Publications
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="monitor" class="h-5 mr-4" stroke-width="2">Presentation</x-feather-icon>
                Presentations
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="umbrella" class="h-5 mr-4" stroke-width="2">Leaves</x-feather-icon>
                Leaves
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="briefcase" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Advisory Meetings
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="trending-up" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Progress Reports
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="paperclip" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Documents
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="flag" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Examiner Status
            </a>
        </li>
        <li>
            <a href="{{ route('scholars.profile.show', auth()->user()) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="user" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Profile
            </a>
        </li>
    </ul>
</aside>

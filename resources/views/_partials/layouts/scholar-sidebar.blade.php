<div class="h-20 flex items-center px-4 mb-8">
    <img src="{{ asset('images/university-logo.png') }}" alt="DU Logo" class="h-12 mr-3">
    <a href="{{ route('scholars.dashboard') }}" class="inline-block logo leading-tight max-w-sm mr-4">
        <h1 class="text-lg font-bold">Department of <br> Computer Science</h1>
    </a>
</div>
<aside class="flex-1 px-4">
    <ul class="font-bold text-white-90 space-y-2">
        @auth('scholars')
        <li>
            <a href="{{ route('scholars.dashboard') }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="grid" class="h-5 mr-4" stroke-width="2">Dashboard</x-feather-icon>
                Dashboard
            </a>
        </li>
        @endauth
        @can('viewAny', App\Models\Pivot\ScholarCoursework::class)
        <li>
            <a href="{{ route('scholars.courseworks.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="book" class="h-5 mr-4" stroke-width="2">Coursework</x-feather-icon>
                Pre-PhD Courseworks
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\Publication::class)
        <li>
            <a href="{{ route('scholars.publications.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="file" class="h-5 mr-4" stroke-width="2">Publications</x-feather-icon>
                Publications
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\Presentation::class)
        <li>
            <a href="{{ route('scholars.presentations.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="monitor" class="h-5 mr-4" stroke-width="2">Presentation</x-feather-icon>
                Presentations
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\Leave::class)
        <li>
            <a href="{{ route('scholars.leaves.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="umbrella" class="h-5 mr-4" stroke-width="2">Leaves</x-feather-icon>
                Leaves
            </a>
        </li>
        @endcan
        @can('viewAny', App\Models\AdvisoryMeeting::class)
        <li>
            <a href="{{ route('scholars.advisory-meetings.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="briefcase" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Advisory Meetings
            </a>
        </li>
        @endcan
        @can('viewAny', ProgressReport::class)
        <li>
            <a href="{{ route('scholars.progress-reports.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="trending-up" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Progress Reports
            </a>
        </li>
        @endcan
        @can('viewAny', ScholarDocument::class)
        <li>
            <a href="{{ route('scholars.documents.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="paperclip" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Documents
            </a>
        </li>
        @endcan
        @can('viewAny', PrePhdSeminar::class)
        <li>
            <a href="{{ route('scholars.pre-phd-seminar.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="airplay" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Pre PhD Seminar
            </a>
        </li>
        @endcan
        @can('viewAny', TitleApproval::class)
        <li>
            <a href="{{ route('scholars.title-approval.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="check-circle" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Title Approval (BRS)
            </a>
        </li>
        @endcan
        @can('viewAny', ScholarExaminer::class)
        <li>
            <a href="{{ route('scholars.examiner.index', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="flag" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Examiner Status
            </a>
        </li>
        @endcan
        <li>
            <a href="{{ route('scholars.profile.show', $scholar) }}" class="flex items-center py-2 px-4 text-white-70 rounded transform transition-transform duration-150 hover:scale-105 focus:scale-105 hover:-translate-y-1 focus:-translate-y-1 hover:text-white focus:text-white hover:bg-magenta-700 focus:bg-magenta-700 hover:shadow focus:shadow focus:outline-none">
                <x-feather-icon name="user" class="h-5 mr-4" stroke-width="2"></x-feather-icon>
                Profile
            </a>
        </li>
    </ul>
</aside>

<div class="page-card m-6">
    <div class="flex items-baseline px-6 mb-4">
        <h1 class="page-header mb-0 px-0 mr-4">Publications</h1>
        @can('create', App\Publication::class)
        <div class="ml-auto flex items-center">
            <select name="publication_type" id=""
                class="btn btn-magenta text-center outline-none cursor-pointer border-none"
                onchange="window.location.replace(this.value)" >

                <option value="" disabled selected> New </option>
                <option value="{{ route('publications.journal.create') }}">
                    New Journal
                </option>
                <option value="{{ route('publications.conference.create') }}">
                    New Conference
                </option>

            </select>
        </div>
        @endcan
    </div>
    @include('publications.journals.index', [
        'journals' => $user->journals
    ])
    @include('publications.conferences.index', [
        'conferences' => $user->conferences
    ])
</div>

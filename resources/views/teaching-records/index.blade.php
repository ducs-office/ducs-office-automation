@extends('layouts.master')
@section('body')
@canany(['start', 'extend'], App\Models\TeachingRecord::class)
<div class="page-card p-6">
    <h2 class="text-xl font-bold mb-2">Manage Record Submission</h1>
    @if(App\Models\TeachingRecord::isAccepting())
        @can('extend', App\Models\TeachingRecord::class)
            @include('_partials.forms.extend-teaching-records')
        @else
            <p class="text-gray-600 font-bold">We've started accepting teaching records. You don't have enough permissions to extend the deadline.</p>
        @endcan
    @else
        @can('start', App\Models\TeachingRecord::class)
            @include('_partials.forms.start-teaching-records')
        @else
            <p class="text-gray-600 font-bold">We're not accepting records. You don't have enough permissions to start accepting submissions.</p>
        @endcan
    @endif
</div>
@endcanany
@can('viewAny', App\Models\TeachingRecord::class)
<div class="page-card pb-0">
    <div class="flex items-center px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">UG Teaching Records</h1>
    </div>
    @php($filters = Request::query('filters'))
    <form class="ml-auto flex items-end py-4 px-6">
        <div class="w-40 mr-2">
            <label for="accepted_after_filter" class="w-full form-label mb-1">Accepted After</label>
            <input id="accepted_after_filter" type="date" name="filters[valid_from]" class="w-full form-input"
                value="{{ $filters['valid_from'] ?? null }}">
        </div>
        <div class="max-w-sm mr-2">
            <label for="course_filters" class="w-full form-label mb-1">By Course</label>
            <select id="course_filters" name="filters[course_id]" type="text" class="w-full form-select">
                <option value="" {{ isset($filters['course_id']) ? '' : 'selected' }}>All</option>
                @foreach ($courses as $course)
                <option value="{{ $course->id }}"
                    {{ ($filters['course_id'] ?? null) == $course->id ? 'selected' : '' }}>
                    {{ $course->name }} ({{ $course->code }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex">
            <button type="button" class="inline-flex items-center btn hover:text-red-700 mr-2"
                onclick="window.location.replace(window.location.pathname)">
                <x-feather-icon name="x-circle" stroke-width="2.5" class="h-5 mr-2">Clear</x-feather-icon>
                Reset
            </button>
            <button type="submit" formaction="{{ route('teaching-records.index') }}"
                class="inline-flex items-center btn btn-magenta mr-2">
                <x-feather-icon name="filter" stroke-width="2.5" class="h-5 mr-2">Filter</x-feather-icon>
                Filter
            </button>
            <button type="submit" formaction="{{ route('teaching-records.export') }}"
                class="inline-flex items-center btn btn-magenta mr-2">
                <x-feather-icon name="download" stroke-width="2.5" class="h-5 mr-2">Export</x-feather-icon>
                Export CSV
            </button>
        </div>
    </form>
    <table class="border-collapse">
        <thead>
            <tr class="bg-gray-300 text-sm tracking-wide">
                <th class="pl-6 pr-4 py-3 uppercase text-xs text-left table-fit">Start From</th>
                <th class="px-4 py-3 uppercase text-xs text-left table-fit">Teacher</th>
                <th class="px-4 py-3 uppercase text-xs text-left table-fit">Status</th>
                <th class="px-4 py-3 uppercase text-xs text-left">College</th>
                <th class="px-4 py-3 uppercase text-xs text-left">Programme</th>
                <th class="px-4 py-3 uppercase text-xs text-left">Course</th>
                <th class="pr-6 pr-4 py-3 uppercase text-xs text-right table-fit">Sem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
            <tr class="hover:bg-gray-200">
                <td class="pl-6 pr-4 py-2 border-b table-fit">{{ $record->valid_from->format('M, Y') }}</td>
                <td class="px-4 py-2 border-b table-fit">{{ $record->teacher->name }}</td>
                <td class="px-4 py-2 border-b table-fit">{{ $record->designation }}</td>
                <td class="px-4 py-2 border-b">{{ $record->college->name }}</td>
                <td class="px-4 py-2 border-b">
                    {{ $record->programmeRevision->programme->name }}
                    <span class="whitespace-no-wrap">(w.e.f {{ $record->programmeRevision->revised_at->year }})</span>
                </td>
                <td class="px-4 py-2 border-b" title="{{ $record->course->code }}">
                    <span
                        class="text-sm tracking-wide bg-gray-300 px-2 py-1 rounded font-mono">{{ $record->course->code }}</span>
                    <span class="leading-relaxed whitespace-no-wrap">{{ $record->course->name}}</span>
                </td>
                <td class="pr-6 pl-4 py-2 border-b table-fit">{{ $record->semester }}</td>
            </tr>
            @empty
            <tr>
                <td class="px-6" colspan="7">
                    <div class="pt-6 pb-4 flex flex-col items-center justify-center text-gray-500">
                        <x-feather-icon name="frown" class="h-16"></x-feather-icon>
                        <p class="mt-4 mb-2  font-bold">
                            Sorry! No Records
                            {{ count(request()->filters ?? []) ? 'found for your query.' : 'added yet.' }}
                        </p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endcan
@endsection

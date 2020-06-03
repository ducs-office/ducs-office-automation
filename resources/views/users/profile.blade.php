@extends('layouts.master')
@section('body')
<div class="m-4 grid gap-8 grid-cols-2 items-start">
    <div class="col-span-2 page-card p-6 overflow-visible">
        <div class="-mt-6 -mx-6 bg-magenta-800 h-48 rounded-t-md flex justify-end items-end p-4">
            @can('updateProfile', $user)
            <a href="#" class="btn inline-flex">
                <x-feather-icon name="edit" class="h-current mr-2"></x-feather-icon>
                Edit
            </a>
            @endif
        </div>
        <div class="-mt-24 space-y-4 text-center mb-8">
            <img src="{{ $user->getAvatarUrl() }}"
                class="flex items-center justify-center w-48 h-48 mx-auto object-cover border-4 border-white bg-white rounded-full shadow-md overflow-hidden"
                alt="{{ $user->name }}'s avatar">
            <div>
                <h2 class="relative text-3xl">
                    {{ $user->name }}
                </h2>
                @if($user->designation)
                    <h3 class="text-xl text-gray-700 mt-2">
                        {{ $user->category }} / {{ $user->designation }}
                    </h3>
                @endif
                @if($user->college)
                    <h4 class="text-lg text-gray-700 italic mt-1 capitalize">
                        {{ $user->college->name }}
                    </h4>
                @endif
            </div>
        </div>
        <x-tabbed-pane current-tab="info">
            <x-slot name="tabs">
                <div class="flex items-center justify-center space-x-3 border-b -mx-6 px-6 mb-6">
                    <x-tab name="info">Basic Info</x-tab>
                    <x-tab name="teaching">Work</x-tab>
                </div>
            </x-slot>

            <x-tab-content tab="info" class="space-y-3 w-full max-w-2xl mx-auto">
                <h3 class="px-3 text-lg font-bold">
                    Basic Information
                </h3>

                <div class="mt-4 flex-1">
                    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
                        <li class="px-4 py-3 flex space-x-4">
                            <h4 class="whitespace-no-wrap font-bold w-48">Email</h4>
                            <p class="flex-1 text-gray-800"> {{ $user->email }}</p>
                        </li>
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">Contact Number</p>
                            <p class="flex-1 text-gray-800">{{ $user->phone ?? '-' }}</p>
                        </li>
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">Address</p>
                            <p class="flex-1 text-gray-800">
                                {{ $user->address ?? '-'}}
                            </p>
                        </li>
                    </ul>
                </div>
            </x-tab-content>

            <x-tab-content tab="teaching" class="space-y-3 w-full max-w-2xl mx-auto">
                <h3 class="px-3 text-lg font-bold">
                    Work Details
                </h3>
                <div class="mt-4 flex-1">
                    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
                        @if($user->isCollegeTeacher() || $user->isFacultyTeacher())
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">Status</p>
                            <p class="flex-1 text-gray-800">{{ $user->status ?? '-' }}</p>
                        </li>
                        @endif
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">Designation</p>
                            <p class="flex-1 text-gray-800">{{ $user->designation ?? '-' }}</p>
                        </li>
                        <li class="px-4 py-3 flex space-x-4">
                            <p class="whitespace-no-wrap font-bold w-48">College/Department</p>
                            <p class="flex-1 text-gray-800">{{ optional($user->college)->name ?? '-' }}</p>
                        </li>
                    </ul>
                </div>
            </x-tab-content>
        </x-tabbed-pane>
    </div>
    @if($user->isCollegeTeacher())
        <div class="page-card p-0 divide-y">
            <div class="px-6 pt-4 pb-3">
                <h4 class="text-xl font-bold">Current Teaching Details</h4>
            </div>
            <ul class="divide-y">
                @forelse($user->teachingDetails as $detail)
                <li class="px-6 py-4">
                    <div class="flex items-baseline">
                        <span class="text-lg text-gray-700 font-mono mr-4">{{ $detail->course->code }}</span>
                        <div class="flex-1">
                            <div class="text-lg capitalize font-bold">{{ $detail->course->name }}</div>
                            <div class="text-gray-700">
                                <span class="capitalize mr-1">{{ $detail->programmeRevision->programme->name }}</span>
                                (w.e.f. {{ $detail->programmeRevision->revised_at->year }})
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <li class="p-4 text-gray-600 font-bold"> Nothing to see here. </li>
                @endforelse
            </ul>
            <div class="px-6 py-3">
                @can('create', App\Models\TeachingRecord::class)
                <form action="{{ route('teachers.profile.submit') }}" method="POST">
                    @csrf_token
                    <button type="submit" class="btn btn-magenta mt-6"> Submit Details </button>
                </form>
                @else
                <button disabled
                    class="btn bg-gray-400 hover:bg-gray-400 cursor-not-allowed border-0">
                    Submit Details
                </button>
                @endcan
            </div>
        </div>
        <div class="page-card p-6">
            <div class="border-b -mx-6 px-6 -mt-6 pt-4 pb-3 mb-3">
                <h4 class="text-xl font-bold flex items-center">
                    <x-feather-icon name="git-commit" class="h-current mr-4"></x-feather-icon>
                    <span>Teaching History</span>
                </h4>
            </div>
            @forelse ($user->teachingRecords->take(5)->groupBy(function($record) {
                    return $record->valid_from->format('M, Y');
                }) as $date => $records)
            <x-timeline-item icon="circle" color="text-gray-400">
                <h4 class="font-bold mb-3">{{ $date }}</h4>
                <ul class="border rounded divide-y">
                    @foreach($records as $record)
                    <li class="px-4 py-2">
                        Taught <b>{{ $record->course->name }} <em>({{ $record->course->code }})</em></b>
                        under {{ $record->programmeRevision->programme->name }}
                        (w.e.f {{ $record->programmeRevision->revised_at->year }})
                        in <em class="underline">{{ $record->college->name }}</em> as
                        <strong>{{ $record->designation }}</strong> teacher.
                    </li>
                    @endforeach
                </ul>
            </x-timeline-item>
            @empty
            <div class="mt-6 text-gray-600 font-bold"> Nothing to see here. </div>
            @endforelse
        </div>
    @endif
</div>
@endsection

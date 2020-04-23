@extends('layouts.research')
@section('body')
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 h-full shadow-md">
            <div class="flex mb-10">
                <div class="flex items-center">
                    <img src="{{ route('scholars.profile.avatar')}}" class="w-24 h-24 object-cover mr-4 border rounded shadow">
                    <h3 class="text-2xl font-bold"> {{$scholar->name}}</h3>
                </div>
                <div class="ml-auto">
                    <div class="mt-2 flex">
                        <h4 class="font-semibold"> Gender </h4>
                        <p class="ml-2"> {{ $genders[$scholar->gender] }}</p>
                    </div>
                    <div class="mt-2">
                        <p class="flex items-center mb-1">
                            <feather-icon name="at-sign" class="h-current mr-2"></feather-icon>
                            <a href="mailto:{{ $scholar->email}}">{{ $scholar->email }}</a>
                        </p>
                        <p class="flex items-center mb-1">
                            <feather-icon name="phone" class="h-current mr-2"></feather-icon>
                            <a href="tel:{{ $scholar->phone_no }}">{{ $scholar->phone_no }}</a>
                        </p>
                        <div class="flex">
                            <feather-icon name="home" class="h-current mr-2"></feather-icon>
                            <address>
                                {{ $scholar->address}}
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Admission
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="my-6 flex-1">
                    <ul class="border rounded-lg overflow-hidden mb-4">
                        <li class="px-4 py-3 border-b last:border-b-0">
                            <div class="flex mt-2">
                                <p class="font-bold"> Category</p>
                                <p class="ml-4 text-gray-800"> {{$categories[$scholar->category] ?? 'not set'}}</p>
                            </div>
                        </li>
                        <li class="px-4 py-3 border-b last:border-b-0">
                            <div class="mt-2 flex">
                                <h4 class="font-bold"> Date of enrollment </h4>
                                <p class="ml-4 text-gray-800"> {{ $scholar->enrollment_date }}</p>
                            </div>
                        </li>
                        <li class="px-4 py-3 border-b last:border-b-0">
                            <div class="flex mt-2">
                                <p class="font-bold"> Admission via</p>
                                <p class="ml-4 text-gray-800"> {{ $admissionCriterias[$scholar->admission_via]['mode'] ?? '-'}}</p>
                            </div>
                        </li>
                        <li class="px-4 py-3 border-b last:border-b-0">
                            <div class="mt-2 flex">
                                <p class="font-bold"> Funding </p>
                                <p class="ml-4 text-gray-800"> {{ $admissionCriterias[$scholar->admission_via]['funding'] ?? '-'}}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Broad Area of Research
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="my-6 flex-1 px-4 py-3 border rounded-lg">
                    <p class="ml-2 font-bold"> {{ $scholar->research_area }}</p>
                </div>
            </div>
            <div class="flex">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Supervisor
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg> 
                </div>
               
                <ul class="flex-col-reverse flex my-6 flex-1 border rounded-lg overflow-hidden">
                    @php
                        $prevDate = $scholar->registerOn
                    @endphp
                    @foreach ($scholar->old_supervisors as $old_supervisor)
                    <li class="border-b first:border-b-0 px-5 p-2">
                        <div class="flex justify-between items-center"> 
                            <div class="w-1/2">
                                <p class="font-bold"> {{ $old_supervisor['name'] }} </p>
                                <div class="flex mt-1 items-center text-gray-700">
                                    <feather-icon name="at-sign" class="h-current">Email</feather-icon>
                                    <p class="ml-1 italic"> {{ $old_supervisor['email'] }} </p>
                                </div>
                            </div>
                            <p class="w-1/2 mr-4 font-bold"> {{ $prevDate }} - {{ $old_supervisor['date']}}</p>    
                            @php
                                $prevDate = $old_supervisor['date']
                            @endphp
                        <div>
                    </li>
                    @endforeach
                    <li class="border-b first:border-b-0 px-5 p-2">
                        <div class="flex justify-between items-center"> 
                            <div class="w-1/2">
                                <p class="font-bold"> {{ $scholar->supervisor->name }} </p>
                                <div class="flex mt-1 items-center text-gray-700">
                                    <feather-icon name="at-sign" class="h-current">Email</feather-icon>
                                    <p class="ml-1 italic"> {{ $scholar->supervisor->email }} </p>
                                </div>
                            </div>
                            <p class="w-1/2 mr-4 font-bold"> {{ $prevDate }} - Present</p>
                        </div>
                    </li>
                </ul>
            </div>
             
            <div class="flex">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Cosupervisor
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg> 
                </div>
               
                <ul class="flex-col-reverse flex my-6 flex-1 border rounded-lg overflow-hidden">
                    @php
                        $prevDate = $scholar->registerOn
                    @endphp
                    @foreach ($scholar->old_cosupervisors as $old_cosupervisor)
                    <li class="border-b first:border-b-0 px-5 p-2">
                        <div class="flex justify-between items-center"> 
                            <div class="w-1/2">
                                @if ($old_cosupervisor['name'])
                                <p class="font-bold"> {{ $old_cosupervisor['name'] }} </p>
                                <p class="text-gray-700 mt-1"> {{ $old_cosupervisor['designation'] }} </p>
                                <p class="text-gray-700 mt-1"> {{ $old_cosupervisor['affiliation'] }} </p>
                                <div class="flex mt-1 items-center text-gray-700">
                                    <feather-icon name="at-sign" class="h-current">Email</feather-icon>
                                    <p class="ml-1 italic"> {{ $old_cosupervisor['email'] }} </p>
                                </div>
                                @else
                                <p class="font-bold"> Cosupervisor Not Assigned </p>
                                @endif
                            </div>
                            <p class="w-1/2 mr-4 font-bold"> {{ $prevDate }} - {{ $old_cosupervisor['date']}}</p>    
                            @php
                                $prevDate = $old_supervisor['date']
                            @endphp
                        <div>
                    </li>
                    @endforeach
                    <li class="border-b first:border-b-0 px-5 p-2">
                        <div class="flex justify-between items-center"> 
                            <div class="w-1/2">
                                @if ($scholar->cosupervisor)
                                <p class="font-bold"> {{ $scholar->cosupervisor->name }} </p>
                                <p class="text-gray-700 mt-1"> {{ $scholar->cosupervisor->designation }} </p>
                                <p class="text-gray-700 mt-1"> {{ $scholar->cosupervisor->affiliation }} </p>
                                <div class="flex mt-1 items-center text-gray-700">
                                    <feather-icon name="at-sign" class="h-current">Email</feather-icon>
                                    <p class="ml-1 italic"> {{ $scholar->cosupervisor->email }} </p>
                                </div>
                                @else
                                <p class="font-bold"> Cosupervisor Not Assigned </p>
                                @endif
                            </div>
                            <p class="w-1/2 mr-4 font-bold"> {{ $prevDate }} - Present</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="flex">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Advisory Committee
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="flex-1 my-6">
                    <ul class="border flex flex-wrap rounded-lg overflow-hidden mb-4">
                        @foreach ($scholar->advisory_committee as $adviser)
                            <li class="px-5 py-5 border-b last:border-b-0 w-1/2">
                                <div class="flex mb-1">
                                    <feather-icon name="pen-tool" class="h-current"></feather-icon>
                                    <p class="ml-2 font-bold"> {{ $adviser['title'] }} {{ $adviser['name'] }} </p>
                                </div>
                                <p class="ml-6 text-gray-700 mb-1"> {{ $adviser['designation'] }} </p>
                                <p class="ml-6 text-gray-700"> {{ $adviser['affiliation'] }} </p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Publications and Presentations --}}
        <div class="bg-white p-6 h-full shadow-md mt-8">
            <div class="flex">
                <div class="w-60 pr-4 relative z-10 -ml-8 my-2">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Publications
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
            </div>

        </div>
         @include('research.scholars.publications.journals.index', [
            'journals' => $scholar->journals
        ])
        @include('research.scholars.publications.conferences.index', [
            'conferences' => $scholar->conferences
        ])

        <div class="bg-white p-6 h-full shadow-md mt-8">
            <div class="flex justify-between">
                <div class="w-60 pr-4 relative z-10 -ml-8 my-2">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Presentations
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
            </div>

            @include('research.scholars.presentations.index', [
                'presentations' => $scholar->presentations,
                'eventTypes' => $eventTypes,
            ])
        </div>

        <div class="bg-white p-6 h-full rounded shadow-md mb-8 mt-6">

            {{-- Courseworks --}}
            <div class="mb-16 flex">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Pre-PhD Coursework
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="flex-1 pl-4">
                    <ul class="border rounded-lg overflow-hidden mb-4">
                        @foreach ($scholar->courseworks as $course)
                            <li class="px-4 py-3 border-b last:border-b-0">
                                <div class="flex items-center">
                                    <div class="w-24">
                                        <span class="px-3 py-1 text-sm font-bold bg-magenta-200 text-magenta-800 rounded-full mr-4">{{ $course->type == 'C' ? 'Core' : 'Elective' }}</span>
                                    </div>
                                    <h5 class="font-bold flex-1">
                                        {{ $course->name }}
                                        <span class="text-sm text-gray-500 font-bold"> ({{ $course->code }}) </span>
                                    </h5>
                                    @if ($course->pivot->completed_at)
                                        <div class="flex items-center pl-4">
                                            <div class="w-5 h-5 inline-flex items-center justify-center bg-green-500 text-white font-extrabold leading-none rounded-full mr-2">&checkmark;</div>
                                            <div>
                                                Completed on {{ $course->pivot->completed_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    @elsecan('scholars.coursework.complete', $scholar)
                                    <form action="{{ route('research.scholars.courseworks.complete', [$scholar, $course]) }}"
                                        method="POST" class="pl-4 leading-none">
                                        @csrf_token @method("PATCH")
                                        <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded font-bold">
                                            Mark Completed
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @can('scholars.coursework.store', $scholar)
                    <button class="w-full btn btn-magenta rounded-lg py-3" @click="$modal.show('add-coursework-modal')">
                        + Add Coursework
                    </button>
                    <v-modal name="add-coursework-modal" height="auto">
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-4">Add Coursework</h3>
                            <form action="{{ route('research.scholars.courseworks.store', $scholar) }}"
                                method="POST" class="flex">
                                @csrf_token
                                <select id="course_ids" name="course_ids[]" class="w-full form-input rounded-r-none">
                                    @foreach ($courses as $course)
                                        <option value="{{$course->id}}">
                                            [{{ $course->code }}] {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none">Add</button>
                            </form>
                        </div>
                    </v-modal>
                    @endcan
                </div>
            </div>

            {{-- Leaves --}}
            @php($icons = [
                App\LeaveStatus::APPROVED => 'check-circle',
                App\LeaveStatus::REJECTED => 'x-circle',
                App\LeaveStatus::RECOMMENDED => 'shield',
                App\LeaveStatus::APPLIED => 'alert-circle'
            ])
            @php($colors = [
                App\LeaveStatus::APPROVED => 'text-green-500',
                App\LeaveStatus::REJECTED => 'text-red-600',
                App\LeaveStatus::RECOMMENDED => 'text-blue-600',
                App\LeaveStatus::APPLIED => 'text-gray-700'
            ])
            <div class="mb-16 flex">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Leaves
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="flex-1 pl-4">
                    <form id="patch-form" method="POST" class="w-0">
                        @csrf_token @method("PATCH")
                    </form>
                    <ul class="w-full border rounded-lg overflow-hidden mb-4">
                        @forelse ($scholar->leaves as $leave)
                        <li class="px-4 py-3 border-b last:border-b-0">
                            <div class="flex items-center">
                                <h5 class="font-bold flex-1">
                                    {{ $leave->reason }}
                                    <div class="text-sm text-gray-500 font-bold">
                                        ({{ $leave->from->format('Y-m-d') }} - {{$leave->to->format('Y-m-d')}})
                                    </div>
                                </h5>
                                <a target="_blank" href="{{ route('research.scholars.leaves.attachment', [$scholar, $leave]) }}" class="btn inline-flex items-center ml-2">
                                    <feather-icon name="paperclip" class="h-current mr-2"></feather-icon>
                                    Attached Document
                                </a>
                                <div class="flex items-center px-4">
                                    <feather-icon name="{{ $icons[$leave->status] }}" class="h-current {{ $colors[$leave->status] }} mr-2" stroke-width="2.5"></feather-icon>
                                    <div class="capitalize">
                                        {{ $leave->status }}
                                    </div>
                                </div>
                                @can('recommend', $leave)
                                <button type="submit" form="patch-form"
                                    class="px-4 py-2 mr-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded font-bold"
                                    formaction="{{ route('research.scholars.leaves.recommend', [$scholar, $leave]) }}">
                                    Recommend
                                </button>
                                @endcan
                                @can('approve', $leave)
                                <button type="submit"
                                    form="patch-form"
                                    class="p-2 mr-1 bg-green-500 hover:bg-green-600 text-white text-sm rounded font-bold"
                                    formaction="{{ route('research.scholars.leaves.approve', [$scholar, $leave]) }}">
                                    <feather-icon name="check" class="h-current" stroke-width="3">Approve</feather-icon>
                                </button>
                                @endcan
                                @can('reject', $leave)
                                <button type="submit"
                                    form="patch-form"
                                    class="p-2 ml-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded font-bold"
                                    formaction="{{ route('research.scholars.leaves.reject', [$scholar, $leave]) }}">
                                    <feather-icon name="x" class="h-current" stroke-width="3">Reject</feather-icon>
                                </button>
                                @endcan
                            </div>
                            <div class="ml-3 border-l-4">
                                @foreach($leave->extensions as $extensionLeave)
                                    <div class="flex items-center ml-6 mt-4">
                                        <h5 class="font-bold flex-1">
                                            {{ $extensionLeave->reason }}
                                            <div class="text-sm text-gray-500 font-bold">
                                                (extension till {{$extensionLeave->to->format('Y-m-d')}})
                                            </div>
                                        </h5>
                                        <a target="_blank" href="{{ route('research.scholars.leaves.attachment', [$scholar, $extensionLeave]) }}" class="btn inline-flex items-center ml-2">
                                            <feather-icon name="paperclip" class="h-current mr-2"></feather-icon>
                                            Attached Document
                                        </a>
                                        <div class="flex items-center px-4">
                                            <feather-icon name="{{ $icons[$extensionLeave->status] }}" class="h-current {{ $colors[$extensionLeave->status] }} mr-2" stroke-width="2.5"></feather-icon>
                                            <div class="capitalize">
                                                {{ $extensionLeave->status }}
                                            </div>
                                        </div>
                                        @can('recommend', $extensionLeave)
                                        <button type="submit" form="patch-form"
                                            class="px-4 py-2 mr-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded font-bold"
                                            formaction="{{ route('research.scholars.leaves.recommend', [$scholar, $extensionLeave]) }}">
                                            Recommend
                                        </button>
                                        @endcan
                                        @can('approve', $extensionLeave)
                                        <button type="submit"
                                            form="patch-form"
                                            class="p-2 mr-1 bg-green-500 hover:bg-green-600 text-white text-sm rounded font-bold"
                                            formaction="{{ route('research.scholars.leaves.approve', [$scholar, $extensionLeave]) }}">
                                            <feather-icon name="check" class="h-current" stroke-width="3">Approve</feather-icon>
                                        </button>
                                        @endcan
                                        @can('reject', $extensionLeave)
                                        <button type="submit"
                                            form="patch-form"
                                            class="p-2 ml-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded font-bold"
                                            formaction="{{ route('research.scholars.leaves.reject', [$scholar, $extensionLeave]) }}">
                                            <feather-icon name="x" class="h-current" stroke-width="3">Reject</feather-icon>
                                        </button>
                                        @endcan
                                    </div>
                                @endforeach
                            </div>
                        </li>
                        @empty
                        <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No Leaves</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Meetings --}}
            <div class="mb-4 flex">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Advisory Meetings
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="flex-1 pl-4">
                    <ul class="border rounded-lg overflow-hidden mb-4">
                        @forelse ($scholar->advisoryMeetings as $meeting)
                        <li class="px-4 py-3 border-b last:border-b-0">
                            <div class="flex items-center">
                                <h5 class="font-bold flex-1">
                                    {{ $meeting->date->format('D M d, Y') }}
                                </h5>
                                <a href="{{ route('research.advisory_meetings.minutes_of_meeting', $meeting) }}"
                                    class="inline-flex items-center underline px-4 py-2 text-gray-900 rounded font-bold">
                                    <feather-icon name="paperclip" class="h-4 mr-2"></feather-icon>
                                    Minutes of Meeting
                                </a>
                            </div>
                        </li>
                        @empty
                        <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No Meetings yet.</li>
                        @endforelse
                    </ul>
                    @can('scholars.advisory_meetings.store', $scholar)
                    <button class="mt-2 w-full btn btn-magenta rounded-lg py-3" @click="$modal.show('add-advisory-meetings-modal')">
                        + Add Meetings
                    </button>
                    <v-modal name="add-advisory-meetings-modal" height="auto">
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-4">Add Advisory Meetings</h3>
                            <form action="{{ route('research.scholars.advisory_meetings.store', $scholar) }}" method="POST"
                                class="flex" enctype="multipart/form-data">
                                @csrf_token
                                <input id="date" name="date" type="date" class="form-input rounded-r-none">
                                <input type="file" name="minutes_of_meeting" id="minutes_of_meeting" class="w-full flex-1 form-input rounded-none" accept="document/*">
                                <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none">Add</button>
                            </form>
                        </div>
                    </v-modal>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

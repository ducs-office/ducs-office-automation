@extends('layouts.scholars')
@section('body')
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 h-full shadow-md">
            <div class="flex mb-6">
                <div class="flex items-center">
                    <img src="{{ route('scholars.profile.avatar')}}" class="w-24 h-24 object-cover mr-4 border rounded shadow">
                    <h3 class="text-2xl font-bold">{{$scholar->name}}</h3>
                </div>
                <div class="ml-auto self-start">
                    <a href=" {{ route('scholars.profile.edit') }} " class="btn btn-magenta">Edit</a>
                </div>
            </div>
            <div class="mb-6">
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
                                <p class="font-bold"> Admission via </p>
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
                        Research
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
                @if ($scholar->supervisorProfile)
                <div class="flex-1 my-6 px-4 py-3 border rounded-lg">
                    <div class="flex items-center">
                        <svg viewBox="0 0 20 20" class="h-current">
                            <g stroke="none" stroke-width="1" fill="currentColor" fill-rule="evenodd">
                                <path d="M3.33333333,8 L10,12 L20,6 L10,0 L-5.55111512e-16,6 L10,6 L10,6.5 L10,8 L3.33333333,8 L3.33333333,8 Z M1.33226763e-15,8 L1.33226763e-15,16 L2,13.7777778 L2,9.2 L2,9.2 L1.33226763e-15,8 L1.11022302e-16,8 L1.33226763e-15,8 Z M10,20 L5,16.9999998 L3,15.8 L3,9.8 L10,14 L17,9.8 L17,15.8 L10,20 L10,20 Z"></path>
                            </g>
                        </svg>
                        <div>
                            <p class="ml-2 font-bold"> {{ $scholar->supervisor->name }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="flex">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Co-Supervisor(s)
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="flex-1 my-6">
                    <ul class="border flex flex-wrap rounded-lg overflow-hidden mb-4">
                        @foreach ($scholar->co_supervisors as $coSupervisor)
                            <li class="px-5 py-5 border-b last:border-b-0 w-1/2">
                                <div class="flex mb-1">
                                    <feather-icon name="pen-tool" class="h-current"></feather-icon>
                                    <p class="ml-2 font-bold"> {{ $coSupervisor['title'] }} {{ $coSupervisor['name'] }} </p>
                                </div>
                                <p class="ml-6 text-gray-700 mb-1"> {{ $coSupervisor['designation'] }} </p>
                                <p class="ml-6 text-gray-700"> {{ $coSupervisor['affiliation'] }} </p>
                            </li>
                        @endforeach
                    </ul>
                </div>
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

        {{-- Pre-PHD Course Work --}}
        <div class="mt-16 mb-16">
            <h3 class="font-bold text-xl mb-4">
                Pre-PhD Coursework
            </h3>
            <ul class="border rounded-lg shadow-md overflow-hidden mb-4 bg-white">
                @foreach ($scholar->courseworks as $course)
                <li class="px-4 py-3 border-b last:border-b-0">
                    <div class="flex items-center">
                        <div class="w-24">
                            <span
                                class="px-3 py-1 text-sm font-bold bg-magenta-200 text-magenta-800 rounded-full mr-4">{{ $course->type == 'C' ? 'Core' : 'Elective' }}</span>
                        </div>
                        <h5 class="font-bold flex-1">
                            {{ $course->name }}
                            <span class="text-sm text-gray-500 font-bold"> ({{ $course->code }}) </span>
                        </h5>
                        @if ($course->pivot->completed_at)
                        <div class="flex items-center pl-4">
                            <div
                                class="w-5 h-5 inline-flex items-center justify-center bg-green-500 text-white font-extrabold leading-none rounded-full mr-2">
                                &checkmark;</div>
                            <div>
                                Completed on {{ $course->pivot->completed_at->format('M d, Y') }}
                            </div>
                        </div>
                        @else
                        <div class="flex items-center pl-4">
                            <div
                                class="w-5 h-5 inline-flex items-center justify-center bg-gray-700 text-white font-extrabold leading-none rounded-full mr-2">
                                &HorizontalLine;</div>
                            <div>
                                On Going
                            </div>
                        </div>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Leaves --}}
        <div class="mb-16">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-bold text-xl mr-4">
                    Leaves
                </h3>
                <button class="btn btn-magenta is-sm text-sm ml-4"
                    @click="$modal.show('apply-for-leave-modal')">
                    Apply For Leaves
                </button>
            </div>

            <ul class="border rounded-lg shadow-md overflow-hidden bg-white mb-4">
                @forelse ($scholar->leaves as $leave)
                <li class="px-4 py-3 border-b last:border-b-0">
                    <div class="flex items-center">
                        <h5 class="font-bold flex-1">
                            {{ $leave->reason }}
                            <span class="text-sm text-gray-500 font-bold">
                                ({{ $leave->from->format('Y-m-d') }} - {{$leave->to->format('Y-m-d')}})
                            </span>
                        </h5>
                        <div class="flex items-center px-4 mr-4">
                            <div class="
                                w-5 h-5 inline-flex items-center justify-center
                                {{ $leave->status === App\LeaveStatus::APPROVED ? 'bg-green-500' : (
                                    $leave->status === App\LeaveStatus::REJECTED ? 'bg-red-600' : 'bg-gray-700'
                                )}}
                                text-white font-extrabold leading-none rounded-full mr-2
                            ">
                                @if($leave->status == App\LeaveStatus::APPROVED)
                                &checkmark;
                                @elseif($leave->status == App\LeaveStatus::REJECTED)
                                &times;
                                @else
                                &HorizontalLine;
                                @endif
                            </div>
                            <div class="capitalize">
                                {{ $leave->status }}
                            </div>
                        </div>
                        <button class="btn btn-magenta text-sm is-sm"
                            @click="$modal.show('apply-for-leave-modal', {
                                'extensionId': {{$leave->id}},
                                'extension_from_date': '{{ $leave->to->format('Y-m-d') }}'
                            })">
                            Extend
                        </button>
                    </div>
                    <div class="ml-6">
                        @foreach($leave->extensions as $extensionLeave)
                        <div class="flex items-center mt-4">
                            <h5 class="font-bold flex-1">
                                {{ $leave->reason }}
                                <span class="text-sm text-gray-500 font-bold">
                                    (extended to {{$leave->to->format('Y-m-d')}})
                                </span>
                            </h5>
                            <div class="flex items-center pl-4">
                                <div class="
                                        w-5 h-5 inline-flex items-center justify-center
                                        {{ $leave->status === App\LeaveStatus::APPROVED ? 'bg-green-500' : (
                                            $leave->status === App\LeaveStatus::REJECTED ? 'bg-red-600' : 'bg-gray-700'
                                        )}}
                                        text-white font-extrabold leading-none rounded-full mr-2
                                    ">
                                    @if($leave->status == App\LeaveStatus::APPROVED)
                                    &checkmark;
                                    @elseif($leave->status == App\LeaveStatus::REJECTED)
                                    &times;
                                    @else
                                    &HorizontalLine;
                                    @endif
                                </div>
                                <div class="capitalize">
                                    {{ $leave->status }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </li>
                @empty
                <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No Leaves</li>
                @endforelse
            </ul>

            <v-modal name="apply-for-leave-modal" height="auto">
                <template v-slot="{ data }">
                    <form action="{{ route('scholars.leaves.store') }}" method="POST" class="p-6">
                        <h3 class="text-lg font-bold mb-4">Add Leave</h3>
                        @csrf_token
                        <input v-if="data('extensionId')" type="hidden" name="extended_leave_id" :value="data('extensionId')">
                        <div class="flex mb-2">
                            <div class="flex-1 mr-2">
                                <label for="from_date" class="w-full form-label mb-1">From Date</label>
                                <input type="date" name="from" id="from_date" placeholder="From Date"
                                    class="w-full form-input" :value="data('extension_from_date', '')">
                            </div>
                            <div class="flex-1 ml-2">
                                <label for="to_date" class="w-full form-label mb-1">To Date</label>
                                <input type="date" name="to" id="to_date" placeholder="To Date" class="w-full form-input">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="reason" class="w-full form-label mb-1">Reason</label>
                            <input type="text" name="reason" autocomplete="#reasons" class="w-full form-input"
                                list="leave_reasons" placeholder="e.g. Maternity Leave">
                            <datalist id="leave_reasons">
                                <option value="Maternity/Child Care Leave">
                                <option value="Medical">
                                <option value="For Work">
                            </datalist>
                        </div>
                        <button type="submit" class="px-5 btn btn-magenta text-sm">Add</button>
                    </form>
                </template>
            </v-modal>
        </div>

        {{-- Meetings --}}
        <div class="mb-16">
            <h3 class="font-bold text-xl mb-4">
                Advisory Meetings
            </h3>
            <ul class="border bg-white rounded-lg overflow-hidden mb-4">
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
        </div>
    </div>
@endsection
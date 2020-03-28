@extends('layouts.scholars')
@section('body')
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 h-full shadow-md">
            <div class="flex mb-6">
                <div class="flex items-center">
                    <img src="{{ route('scholars.profile.avatar')}}" class="w-24 h-24 object-cover mr-4 border rounded shadow">
                    <h3 class="text-2xl font-bold"> {{$scholar->name}}</h3>
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
        @include('scholars.publications.index', [
            'publications' => $scholar->publications
        ])
        @include('scholars.presentations.index', [
            'presentations' => $scholar->presentations
        ])
    </div>
@endsection
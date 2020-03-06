@extends('layouts.scholars')
@section('body')
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 h-full shadow-md">
            <div class="flex items-center mb-4">
                <img src="{{ route('scholars.profile.avatar')}}" class="w-24 h-24 object-cover mr-4 border rounded shadow">
                <h3 class="text-2xl font-bold"> {{$scholar->name}}</h3>
                <div class="ml-auto self-start">
                    <a href=" {{ route('scholars.profile.edit') }} " class="btn btn-magenta">Edit</a>
                </div>
            </div>
            <div class="relative z-10 -ml-6 my-4 mt-8">
                <h5 class="relative z-20 pl-4 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                    Personal Details
                </h5>
            </div>
            <div class="mt-2 flex">
                <h4 class="font-semibold"> Gender: </h4>
                <p class="ml-2"> {{ $genders[$scholar->gender] }}</p>
            </div>
            <div class="flex mt-2">
                <p class="font-semibold"> Category:</p>
                <p class="ml-2"> {{$categories[$scholar->category] ?? 'not set'}}</p>
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
            <div class="relative z-10 -ml-6 my-4 mt-8">
                <h5 class="relative z-20 pl-4 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                    Admission Details
                </h5>
            </div>
            <div class="mt-2 flex">
                <h4 class="font-semibold"> Date of enrollment: </h4>
                <p class="ml-2"> {{ $scholar->enrollment_date }}</p>
            </div>
            <div class="flex mt-2">
                <p class="font-semibold"> Admission via: </p>
                <p class="ml-2"> {{ $admissionCriterias[$scholar->admission_via]['mode'] ?? 'not set'}}</p>
            </div>
            
            <div class="mt-2 flex">
                <p class="font-semibold"> Funding: </p>
                <p class="ml-2"> {{ $admissionCriterias[$scholar->admission_via]['funding'] ?? 'not set'}}</p>
            </div>
            <div class="relative z-10 -ml-6 my-4 mt-8">
                <h5 class="relative z-20 pl-4 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                    Research Details
                </h5>
            </div>
            <div class="mt-2 flex">
                <h4 class="font-semibold"> Broad Area of Research: </h4>
                <p class="ml-2"> {{ $scholar->research_area }}</p>
            </div> 
            <div class="mt-4">
                <h3 class="font-bold"> Supervisor </h3>
            </div>
            <div class="flex items-center mt-2">
                <svg viewBox="0 0 20 20" class="h-current">
                    <g stroke="none" stroke-width="1" fill="currentColor" fill-rule="evenodd">
                        <path d="M3.33333333,8 L10,12 L20,6 L10,0 L-5.55111512e-16,6 L10,6 L10,6.5 L10,8 L3.33333333,8 L3.33333333,8 Z M1.33226763e-15,8 L1.33226763e-15,16 L2,13.7777778 L2,9.2 L2,9.2 L1.33226763e-15,8 L1.11022302e-16,8 L1.33226763e-15,8 Z M10,20 L5,16.9999998 L3,15.8 L3,9.8 L10,14 L17,9.8 L17,15.8 L10,20 L10,20 Z"></path>
                    </g>
                </svg>
                <p class="ml-2 text"> {{ $scholar->supervisor->name }}  </p>
            </div>
            <div class="mt-4">
                <h3 class="font-bold">Co-Supervisors</h3>
                @foreach ($scholar->co_supervisors as $coSupervisor)
                    <div class="mt-2">
                        <div class="flex">
                            <feather-icon name="pen-tool" class="h-current"></feather-icon>
                            <p class="ml-2"> {{ $coSupervisor['title'] }} {{ $coSupervisor['name'] }} </p>
                        </div>
                        <p class="ml-6 text-gray-700"> {{ $coSupervisor['designation'] }} </p>
                        <p class="ml-6 text-gray-700"> {{ $coSupervisor['affiliation'] }} </p>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                <h3 class="font-bold">Advisory Committee</h3>
                @foreach ($scholar->advisory_committee as $adviser)
                    <div class="mt-2">
                        <div class="flex">
                            <feather-icon name="pen-tool" class="h-current"></feather-icon>
                            <p class="ml-2"> {{ $adviser['title'] }} {{ $adviser['name'] }} </p>
                        </div>
                        <p class="ml-6 text-gray-700"> {{ $adviser['designation'] }} </p>
                        <p class="ml-6 text-gray-700"> {{ $adviser['affiliation'] }} </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
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
            <div>
                <p> {{ $scholar->enrollment_date }}</p>
            </div>
            <div>
                <p> {{ $genders[$scholar->gender] }}</p>
            </div>
            <div>
                <p> {{ $scholar->research_area}} </p>
            </div>
            <address>
                {{ $scholar->address}}
            </address>
            <p class="flex items-center">
                <feather-icon name="at-sign" class="h-current mr-2"></feather-icon>
                <a href="mailto:{{ $scholar->email}}">{{ $scholar->email }}</a>
            </p>
            <p class="flex items-center">
                <feather-icon name="phone" class="h-current mr-2"></feather-icon>
                <a href="tel:{{ $scholar->phone_no }}">{{ $scholar->phone_no }}</a>
            </p>
            <div class="mt-4 mb-2">
                <h3 class="font-bold mb-4"> Admission Details</h3>
                <div class="flex">
                    <p class="font-semibold"> Category:</p>
                    <p class="ml-2"> {{$categories[$scholar->category] ?? 'not set'}}</p>
                </div>
                <div class="flex">
                    <div class="flex">
                        <p class="font-semibold"> Admission via: </p>
                        <p class="ml-2"> {{ $admission_criterias[$scholar->admission_via]['mode'] ?? 'not set'}}</p>
                    </div>
                    
                    <div class="ml-4 flex">
                        <p class="font-semibold"> Funding: </p>
                        <p class="ml-2"> {{ $admission_criterias[$scholar->admission_via]['funding'] ?? 'not set'}}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="font-bold"> Supervisor </h3>
                <p> {{ $scholar->supervisor->name }} </p>
            </div>
            <div class="mt-4">
                <h3 class="font-bold">Co-Supervisors</h3>
                <div class="mt-2">
                    @foreach ($scholar->co_supervisors as $co_supervisor)
                        <p> {{ $co_supervisor['title'] }} </p>
                        <p> {{ $co_supervisor['name'] }} </p>
                        <p> {{ $co_supervisor['designation'] }} </p>
                        <p> {{ $co_supervisor['affiliation'] }} </p>
                    @endforeach
                </div>
            </div>
            <div class="mt-4">
                <h3 class="font-bold">Advisory Committee</h3>
                <div mt-2>
                    @foreach ($scholar->advisory_committee as $advisory)
                        <p> {{ $advisory['title'] }} </p>
                        <p> {{ $advisory['name'] }} </p>
                        <p> {{ $advisory['designation'] }} </p>
                        <p> {{ $advisory['affiliation'] }} </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
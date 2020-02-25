@extends('layouts.scholars')
@section('body')
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 h-full shadow-md">
            <div class="flex items-center mb-4">
                <img src="{{ route('staff.scholars.avatar', $scholar)}}" class="w-24 h-24 object-cover mr-4 border rounded shadow">
                <h3 class="text-2xl font-bold"> {{$scholar->name}}</h3>
            </div>
            <address>
                {{ $scholar->profile->address}}
            </address>
            <p class="flex items-center">
                <feather-icon name="at-sign" class="h-current mr-2"></feather-icon>
                <a href="mailto:{{ $scholar->email}}">{{ $scholar->email }}</a>
            </p>
            <p class="flex items-center">
                <feather-icon name="phone" class="h-current mr-2"></feather-icon>
                <a href="tel:{{ $scholar->profile->phone_no }}">{{ $scholar->profile->phone_no }}</a>
            </p>
            <div class="mt-4 mb-2">
                <h3 class="font-bold"> Admission Details</h3>
                <div class="flex">
                    <p class="font-semibold"> Category:</p>
                    <p class="ml-2"> {{$categories[$scholar->profile->category] ?? 'not set'}}</p>
                </div>
                <div class="flex">
                    <p class="font-semibold"> Admission via:</p>
                    <p class="ml-2"> {{ $admission_criterias[$scholar->profile->admission_via] ?? 'not set'}}</p>
                </div>
            </div>

        </div>
    </div>
@endsection
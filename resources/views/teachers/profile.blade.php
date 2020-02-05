@extends('layouts.teachers')
@section('body')
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 h-full rounded shadow-md">
            <div class="flex items-center mb-4">
                <img src="{{ route('teachers.profile.avatar') }}" class="w-24 h-24 object-cover mr-4 border rounded shadow">
                <div>
                    <h3 class="text-2xl font-bold">{{ $teacher->name }}</h3>
                    <h5 class="text-xl text-gray-700 font-medium mb-2">{{ $teacher->profile->getDesignation() }}</h5>
                    <div class="flex items-center text-xl text-gray-700 font-medium">
                        <svg viewBox="0 0 20 20" class="h-current">
                            <g stroke="none" stroke-width="1" fill="currentColor" fill-rule="evenodd">
                                <path d="M3.33333333,8 L10,12 L20,6 L10,0 L-5.55111512e-16,6 L10,6 L10,6.5 L10,8 L3.33333333,8 L3.33333333,8 Z M1.33226763e-15,8 L1.33226763e-15,16 L2,13.7777778 L2,9.2 L2,9.2 L1.33226763e-15,8 L1.11022302e-16,8 L1.33226763e-15,8 Z M10,20 L5,16.9999998 L3,15.8 L3,9.8 L10,14 L17,9.8 L17,15.8 L10,20 L10,20 Z"></path>
                            </g>
                        </svg>
                        <h5 class="ml-2">{{ ($college = $teacher->profile->college) ? $college->name : 'Not Set' }}</h5>
                    </div>
                </div>
                <div class="ml-auto self-start">
                    <a href="{{ route('teachers.profile.edit') }}" class="btn btn-magenta">Edit</a>
                </div>
            </div>
            <address>
                {{ $teacher->profile->address }}
            </address>
            <p class="flex items-center">
                <feather-icon name="at-sign" class="h-current mr-2"></feather-icon>
                <a href="mailto:{{ $teacher->email }}">{{ $teacher->email }}</a>
            </p>
            <p class="flex items-center">
                <feather-icon name="phone" class="h-current mr-2"></feather-icon>
                <a href="tel:{{ $teacher->profile->phone }}">+91 {{ $teacher->profile->phone_no }}</a>
            </p>

            <div class="relative z-10 -ml-8 my-4">
                <h5 class="relative z-20 pl-8 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                    Teaching Details
                </h5>
                <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                    <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                </svg>
            </div>
            @if($teacher->profile->teaching_details->count())
            <h6 class="font-semibold mb-2">Present</h6>
            <ul>
                @foreach($teacher->profile->teaching_details as $detail)
                <li>
                    {{-- @php(dd($detail->programme_revision)) --}}
                    {{ $detail->programme_revision->programme->name }} -
                    {{ $detail->course->name }}</li>
                @endforeach
            </ul>
            @else
            <p class="text-gray-600 font-bold">Nothing to show here.</p>
            @endif
        </div>
    </div>
@endsection

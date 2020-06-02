@extends('layouts.master')
@section('body')
<div class="page-card my-4 mx-auto lg:w-2/3 p-6">
    <h2 class="page-header px-0">New College</h2>
    <form action="{{ route('staff.colleges.index') }}" method="POST">
        @csrf_token
        <div class="flex items-end mb-2">
            <div class="w-32 mr-1">
                <label for="college_code" class="w-full form-label mb-1">College Code <span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="college_code" type="text" name="code" class="w-full form-input" value="{{ old('code') }}" required>
            </div>
            <div class="flex-1 ml-1">
                <label for="college_name" class="w-full form-label mb-1">College Name<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="college_name" type="text" name="name" class="w-full form-input" value="{{ old('name') }}" required>
            </div>
        </div>
        <div class="mb-2">
            <label for="college_website" class="w-full form-label mb-1">Website<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="college_website" type="text" name="website" class="w-full form-input" value="{{ old('website', 'http://') }}" required>
        </div>
        <div class="mb-2">
            <label for="college_address" class="w-full form-label mb-1">Address<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="college_address" name="address" type="text" class="w-full form-input" required value="{{ old('address') }}">
        </div>
        <div class="mb-2">
            <label for="programme" class="w-full form-label mb-1">Programmes <span
                    class="h-current text-red-500 text-lg">*</span></label>
            <select name="programmes[]" id="programme" class="w-full form-multiselect" multiple required>
                @foreach ($programmes as $programme)
                <option value="{{$programme->id}}">{{$programme->code}} - {{ucwords($programme->name)}}</option>
                @endforeach
            </select>
        </div>
        <div class="relative z-10 -ml-8 my-4">
            <h5 class="relative z-20 pl-8 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">Principal Information</h5>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>
        <div class="mb-2">
            <label for="college_principal_name" class="w-full form-label mb-1">Principal Name<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="college_principal_name" type="text" name="principal_name"
            class="w-full form-input" value="{{ old('principal_name') }}" required>
        </div>
        <div class="flex items-end mb-2">
            <div class="flex-1 mr-1">
                <label for="college_principal_phone1" class="w-full form-label mb-1">Phone <span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="college_principal_phone1" type="text" name="principal_phones[]" class="w-full form-input" value="{{ old('principal_phones', ['', ''])[0] }}" required>
            </div>
            <div class="flex-1 ml-1">
                <label for="college_principal_phone2" class="w-full form-label mb-1">Alternate Phone</label>
                <input id="college_principal_phone2" type="text" name="principal_phones[]" class="w-full form-input" value="{{ old('principal_phones', ['', ''])[1] }}">
            </div>
        </div>
        <div class="flex items-end mb-2">
            <div class="flex-1 mr-1">
                <label for="college_principal_email1" class="w-full form-label mb-1">Email <span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="college_principal_email1" type="email" name="principal_emails[]" class="w-full form-input" value="{{ old('principal_emails', ['', ''])[0] }}" required>
            </div>
            <div class="flex-1 ml-1">
                <label for="college_principal_email2" class="w-full form-label mb-1">Alternate Email</label>
                <input id="college_principal_email2" type="email" name="principal_emails[]" class="w-full form-input" value="{{ old('principal_emails', ['', ''])[1] }}">
            </div>
        </div>

        <div class="mt-5">
            <button type="submit" class="btn btn-magenta">Create</button>
        </div>
    </form>
</div>
@endsection

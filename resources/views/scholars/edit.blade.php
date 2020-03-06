@extends('layouts.scholars')
@section('body')
    <div class="container mx-auto p-4">
        <form class="bg-white p-6 h-full shadow-md" action="{{ route('scholars.profile.update') }}"
            method="POST" enctype="multipart/form-data">
            @csrf_token @method('PATCH')
            <div class="flex items-center mb-4">
                <image-upload-input id="profile_picture"
                    name="profile_picture"
                    class="relative group mr-4 cursor-pointer"
                    placeholder-src="{{ route('scholars.profile.avatar') }}">
                    <template v-slot="{ imageUrl }">
                        <img :src="imageUrl" class="w-32 h-32 object-cover rounded border shadow">
                        <div class="absolute inset-0 hidden group-hover:flex items-center justify-center bg-black-50 text-white p-4">
                            <feather-icon name="camera" class="flex-shrink-0 h-6">Camera</feather-icon>
                            <span class="ml-3 group-hover:underline">Upload Picture</span>
                        </div>
                    </template>
                </image-upload-input>
            </div>
            <div class="relative z-10 -ml-6 my-4 mt-8">
                <h5 class="relative z-20 pl-4 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                    Personal Details
                </h5>
            </div>
            <div class="flex items-baseline ">
                <label for="gender" class="block form-label mr-2"> Gender:</label>
                <select id="gender" name="gender" class="block form-input">
                    <option value="" class="text-gray-600" selected>Select your gender </option>
                    @foreach ($genders as $acronym => $gender)
                    <option value=" {{ $acronym }}"
                        {{ $acronym === old("gender", $scholar->gender) ? 'selected': '' }}>
                        {{ $gender }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-baseline mt-2">
                <label for="category" class="block form-label mr-2"> Category:</label>
                <select id="category" name="category" class="block form-input">
                    <option value="" class="text-gray-600" selected>Choose a category </option>
                    @foreach ($categories as $acronym => $category)
                    <option value=" {{ $acronym }}"
                        {{ $acronym === old("category", $scholar->category) ? 'selected': '' }}>
                        {{ $category }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mt-2 flex items-baseline ">
                <label for="email" class="block form-label">Email:</label>
                <input id="email" type="email" name="email"
                    class="block w-auto form-input ml-2"
                    disabled
                    value="{{ $scholar->email }}">
            </div>
            <div class="mt-2 flex items-baseline ">
                <label for="phone_no" class="block form-label">Phone Number:</label>
                <input id="phone_no" type="text" name="phone_no" class="block w-auto form-input" value="{{ old('phone_no', $scholar->phone_no) }}">
            </div>
            <div class="mt-2 flex items-baseline ">
                <label for="address" class="block form-label mb-1">Address:</label>
                <textarea id="address" name="address" class="block w-auto form-input ml-2">{{ old('address', $scholar->address) }}</textarea>
            </div>
            <div class="relative z-10 -ml-6 my-4 mt-8">
                <h5 class="relative z-20 pl-4 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                    Admission Details
                </h5>
            </div>
            <div class="mt-4">
                <div class="mt-2 flex items-baseline ">
                    <label for="enrollment_date" class="block form-label">Date of enrollment:</label>
                    <input id="enrollment_date" type="date" name="enrollment_date" class="block w-auto form-input ml-2" value="{{ old('phone_no', $scholar->enrollment_date) }}">
                </div>
                <div class="flex items-baseline mt-2">
                    <label for="admission_via" class="block form-label mr-2">Admission Via:</label>
                    <select id="admission_via" name="admission_via" class="block form-input">
                        <option value="" selected> Choose the mode of admission </option>
                        @foreach ($admissionCriterias as $acronym => $admission_via)
                        <option value=" {{ $acronym }}"
                            {{ $acronym === old("admission_via", $scholar->admission_via) ? 'selected': '' }}>
                            {{ $admission_via['mode'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="relative z-10 -ml-6 my-4 mt-8">
                <h5 class="relative z-20 pl-4 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                    Research Details
                </h5>
            </div>
            <div class="mt-4">
                <div class="mt-2 flex items-baseline ">
                    <label for="research_area" class="block form-label"> Broad Area of Research: </label>
                    <textarea id="research_area"  name="research_area" class="block w-auto form-input ml-2">{{ old('research_area', $scholar->research_area) }}</textarea>
                </div>
            </div>
            <div class="flex items-baseline mt-2">
                <label for="supervisor_profile_id" class="block form-label mr-2"> Supervisor </label>
                <select name="supervisor_profile_id" id="supervisor" class="block form-input">
                    <option value="" class="text-gray-600" selected> Select your supervisor </option>
                    @foreach ($supervisorProfiles as $name => $id)
                        <option value=" {{ $id }} "
                            {{ $id === old('supervisor_profile_id', $scholar->supervisor_profile_id)? 'selected':''}} >
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mt-5">
                <button type="submit" class="btn btn-magenta">Save Changes</button>
            </div>
        </form>
    </div>
@endsection
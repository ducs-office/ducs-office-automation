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
            <div class="mb-2">
                <label for="address" class="block form-label mb-1">Address</label>
                <textarea id="address" name="address" class="block w-auto form-input">{{ old('address', $scholar->profile->address) }}</textarea>
            </div>
            <div class="mb-2">
                <label for="email" class="block form-label mb-1">Email</label>
                <input id="email" type="email" name="email"
                    class="block w-auto form-input"
                    disabled
                    value="{{ $scholar->email }}">
            </div>
            <div class="mb-2">
                <label for="phone_no" class="block form-label mb-1">Phone Number</label>
                <input id="phone_no" type="text" name="phone_no" class="block w-auto form-input" value="{{ old('phone_no', $scholar->profile->phone_no) }}">
            </div>
            <div class="mt-4 mb-2">
                <h3 class="font-bold mb-2"> Admission Details</h3>
                <div class="flex items-baseline ">
                    <label for="category" class="block form-label mr-2"> Category:</label>
                    <select id="category" name="category" class="block form-input">
                        <option value="" selected>Choose a category </option>
                        @foreach ($categories as $acronym => $category)
                        <option value=" {{ $acronym }}"
                            {{ $acronym === old("category", $scholar->profile->category) ? 'selected': '' }}>
                            {{ $category }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-baseline mt-2">
                    <label for="admission_via" class="block form-label mr-2">Admission Via:</label>
                    <select id="admission_via" name="admission_via" class="block form-input">
                        <option value="" selected> Choose the mode of admission </option>
                        @foreach ($admission_criterias as $acronym => $admission_via)
                        <option value=" {{ $acronym }}"
                            {{ $acronym === old("admission_via", $scholar->profile->admission_via) ? 'selected': '' }}>
                            {{ $admission_via }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-5">
                <button type="submit" class="btn btn-magenta">Save Changes</button>
            </div>
        </form>
    </div>
@endsection
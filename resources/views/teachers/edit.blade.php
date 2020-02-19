@extends('layouts.teachers')
@section('body')
<div class="container mx-auto p-4">
    <form class="bg-white p-6 h-full rounded shadow-md" action="{{ route('teachers.profile.update') }}"
        method="POST" enctype="multipart/form-data">
        @csrf_token @method('PATCH')
        <div class="flex items-center mb-4">
            <image-upload-input id="profile_picture"
                name="profile_picture"
                class="relative group mr-4 cursor-pointer"
                placeholder-src="{{ route('teachers.profile.avatar') }}">
                <template v-slot="{ imageUrl }">
                    <img :src="imageUrl" class="w-32 h-32 object-cover rounded border shadow">
                    <div class="absolute inset-0 hidden group-hover:flex items-center justify-center bg-black-50 text-white p-4">
                        <feather-icon name="camera" class="flex-shrink-0 h-6">Camera</feather-icon>
                        <span class="ml-3 group-hover:underline">Upload Picture</span>
                    </div>
                </template>
            </image-upload-input>
            <div>
                <h3 class="text-2xl font-bold mb-1">{{ $teacher->name }}</h3>
                <select id="designation" type="text" name="designation" class="form-input font-bold mb-2">
                    @foreach($designations as $value => $designation)
                    <option value="{{ $value }}"
                    {{ $value === old('designation', $teacher->profile->desingation) ? 'selected' : '' }}>
                        {{ $designation }}
                    </option>
                    @endforeach
                </select>
                <div class="flex items-center text-lg text-gray-700 font-medium">
                    <svg viewBox="0 0 20 20" class="h-current">
                        <g stroke="none" stroke-width="1" fill="currentColor" fill-rule="evenodd">
                            <path
                                d="M3.33333333,8 L10,12 L20,6 L10,0 L-5.55111512e-16,6 L10,6 L10,6.5 L10,8 L3.33333333,8 L3.33333333,8 Z M1.33226763e-15,8 L1.33226763e-15,16 L2,13.7777778 L2,9.2 L2,9.2 L1.33226763e-15,8 L1.11022302e-16,8 L1.33226763e-15,8 Z M10,20 L5,16.9999998 L3,15.8 L3,9.8 L10,14 L17,9.8 L17,15.8 L10,20 L10,20 Z">
                            </path>
                        </g>
                    </svg>
                    <select id="college_id" type="text" name="college_id" class="form-input ml-2">
                        @foreach($colleges as $id => $college)
                        <option value="{{ $id }}"
                            {{ $id === old('college_id', $teacher->profile->college_id) ? 'selected' : '' }}>
                            {{ $college }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <label for="email" class="block form-label mb-1">Email</label>
            <input id="email" type="email" name="email"
                class="block w-auto form-input"
                disabled
                value="{{ $teacher->email }}">
        </div>
        <div class="mb-2">
            <label for="phone_no" class="block form-label mb-1">Phone Number</label>
            <input id="phone_no" type="text" name="phone_no" class="block w-auto form-input" value="{{ old('phone_no', $teacher->profile->phone_no) }}">
        </div>
        <div class="mb-2">
            <label for="address" class="block form-label mb-1">Address</label>
            <textarea id="address" name="address" class="block w-auto form-input">{{ old('address', $teacher->profile->address) }}</textarea>
        </div>

        <div class="relative z-10 -ml-8 my-4">
            <h5 class="relative z-20 pl-8 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                Teaching Details
            </h5>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>

        <p class="my-3 text-gray-700"> Choose upto 3 programme and courses you're teaching in current semester.</p>

        @foreach($teacher->profile->teaching_details as $index => $detail)
        <div class="flex items-end mb-2 -mx-2">
            <div class="mx-2">
                <label for="programme-{{$index + 1}}" class="block form-label mb-1">Programme {{ $index + 1 }}</label>
                <select id="programme-{{$index + 1}}" name="teaching_details[{{$index}}][programme_revision]"
                class="block form-input">
                    <option value="" selected>Choose a Programme</option>
                    @foreach ($programmes as $id => $programme)
                        <option value="{{ $id }}"
                            {{ $id === old("teaching_details[{$index}][programme_revision]", $detail->programme_revision_id) ? 'selected' : '' }}>
                            {{ $programme }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mx-2">
                <label for="course-{{ $index + 1 }}" class="block form-label mb-1">Course {{ $index + 1 }}</label>
                <select id="course-{{ $index + 1 }}" name="teaching_details[{{$index}}][course]"
                    class="block form-input">
                    <option value="">Choose Course</option>
                    @foreach ($courses as $id => $course)
                    <option value="{{ $id }}"
                        {{ $id === old("teaching_details[{$index}][course]", $detail->course_id) ? 'selected' : '' }}>
                        {{ $course }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endforeach
        @php($details_count = $teacher->profile->teaching_details->count())
        @foreach(range(1, 3 - $details_count) as $index => $n)
        <div class="flex items-end mb-2 -mx-2">
            <div class="mx-2">
                <label for="programme-{{ $n + $details_count }}" class="block form-label mb-1">Programme {{ $n + $details_count }}</label>
                <select id="programme-{{ $n + $details_count }}" name="teaching_details[{{$index + $details_count}}][programme_revision]"
                class="block form-input">
                    <option value="" selected>Choose a Programme</option>
                    @foreach ($programmes as $id => $programme)
                    <option value="{{ $id }}"
                        {{ $id === old("teaching_details[" . ($index + $details_count) . "][programme_revision]") ? 'selected' : '' }}>
                        {{ $programme }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mx-2">
                <label for="course-{{ $n + $details_count }}" class="block form-label mb-1">Course {{ $n + $details_count  }}</label>
                <select id="course-{{ $n + $details_count  }}" name="teaching_details[{{$index + $details_count}}][course]"
                class="block form-input">
                    <option value="">Choose Course</option>
                    @foreach ($courses as $id => $course)
                    <option value="{{ $id }}"
                        {{ $id === old("teaching_details[" . ($index + $details_count) . "][course]") ? 'selected' : '' }}>
                        {{ $course }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endforeach

        <div class="mt-5">
            <button type="submit" class="btn btn-magenta">Save Changes</button>
        </div>
    </form>
</div>
@endsection

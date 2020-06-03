@extends('layouts.scholars')
@section('body')
    <div class="container mx-auto p-4">
        <form class="page-card p-6 overflow-visible space-y-6" action="{{ route('scholars.profile.update') }}"
            method="POST" enctype="multipart/form-data">
            @csrf_token @method('PATCH')
            <div class="flex items-center">
                <image-upload-input id="profile_picture"
                    name="profile_picture"
                    class="relative group mr-4 cursor-pointer"
                    placeholder-src="{{ $scholar->getAvatarUrl() }}">
                    <template v-slot="{ imageUrl }">
                        <img :src="imageUrl" class="w-32 h-32 object-cover rounded border shadow">
                        <div class="absolute inset-0 hidden group-hover:flex items-center justify-center bg-black-50 text-white p-4">
                            <x-feather-icon name="camera" class="flex-shrink-0 h-6">Camera</x-feather-icon>
                            <span class="ml-3 group-hover:underline">Upload Picture</span>
                        </div>
                    </template>
                </image-upload-input>
            </div>


            <div class="max-w-lg space-y-3">
                <div class="flex items-baseline">
                    <label for="gender" class="w-48 form-label">Gender:</label>
                    <select id="gender" name="gender" class="w-full form-select flex-1">
                        <option value="" class="text-gray-600" selected disabled>Select your Gender</option>
                        @foreach ($genders as $gender)
                        <option value="{{ $gender }}"
                            {{ $gender === old('gender', (string)$scholar->gender) ? 'selected': '' }}>
                            {{ $gender }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-baseline">
                    <label for="category" class="w-48 form-label">Category:</label>
                    <select id="category" name="category" class="w-full form-select flex-1 ">
                        <option value="" class="text-gray-600" selected disabled>Choose a category </option>
                        @foreach ($categories as $category)
                        <option value="{{ $category }}"
                            {{ $category === old("category", (string)$scholar->category) ? 'selected': '' }}>
                            {{ $category }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-baseline">
                    <label for="email" class="w-48 form-label">Email:</label>
                    <input id="email" type="email" name="email"
                        class="w-full form-input flex-1"
                        disabled
                        value="{{ $scholar->email }}">
                </div>
                <div class="flex items-baseline">
                    <label for="phone" class="w-48 form-label">Phone Number:</label>
                    <input id="phone" type="text" name="phone" class="w-full form-input flex-1" value="{{ old('phone', $scholar->phone) }}">
                </div>
                <div class="flex items-baseline">
                    <label for="address" class="w-48 form-label">Address:</label>
                    <textarea id="address" name="address" class="w-full form-textarea flex-1">{{ old('address', $scholar->address) }}</textarea>
                </div>
            </div>

            <div class="space-y-4">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Admission
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="max-w-lg space-y-2">
                    <div class="flex items-baseline">
                        <label for="enrollment_date" class="w-48 form-label">Date of enrollment:</label>
                        <input id="enrollment_date" type="date" name="enrollment_date" class="w-full form-input flex-1" value="{{ old('date', $scholar->enrollment_date) }}">
                    </div>
                    <div class="flex items-baseline">
                        <label for="enrolment_id" class="w-48 form-label">Enrollment Number:</label>
                        <input id="enrolment_id" type="text" name="enrolment_id" class="w-full form-input flex-1" value="{{ old('date', $scholar->enrolment_id) }}">
                    </div>
                    <div class="flex items-baseline">
                        <label for="admission_mode" class="w-48 form-label">Admission Mode:</label>
                        <select id="admission_mode" name="admission_mode" class="w-full form-select flex-1">
                            <option value="" selected> Choose the mode of admission </option>
                            @foreach ($admissionModes as $admissionMode)
                            <option value="{{ $admissionMode }}"
                                {{ $admissionMode === old('admission_mode', (string) $scholar->admission_mode) ? 'selected': '' }}>
                                {{ $admissionMode }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-baseline">
                        <label for="funding" class="w-48 form-label">Funding</label>
                        <select id="funding" name="funding" class="w-full form-select flex-1">
                            <option value="" selected> Choose the funding type </option>
                            @foreach ($fundings as $funding)
                            <option value="{{ $funding }}"
                                {{ $funding === old('funding', (string) $scholar->funding) ? 'selected': '' }}>
                                {{ $funding }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <add-remove-elements :existing-elements="{{ collect($scholar->education_details)->map->toArray()->toJson() }}">
                <template v-slot="{ elements, addElement, removeElement}">
                    <div class="flex w-2/6 items-center">
                        <div class="w-64 pr-4 relative z-10 -ml-8 mb-4">
                            <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                                Education Details
                            </h3>
                            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                            </svg>
                        </div>
                        <button v-on:click.prevent="addElement"
                                v-if="elements.length < 4"
                                class="ml-auto text-blue-700 bg-gray-300 btn is-sm"
                            > +
                        </button>
                    </div>
                    <h6 class="mb-6 text-gray-800 text-sm">You can add a maximum of 4 education records only. Minimum 1 is required. <span class="text-red-600">*</span></h6>
                    <div class="flex items-baseline mb-2 space-x-2">
                        <label for="education_details[][degree]" class="w-full flex-1 form-label">Degree</label>
                        <label for="education_details[][subject]" class="w-full flex-1 form-label">Subject</label>
                        <label for="education_details[][institute]" class="w-full flex-1 form-label">Institue</label>
                        <label for="education_details[][year]" class="w-24 form-label">Year</label>
                        <label for="" class="inline-block w-8"></label>
                    </div>
                    <div class="space-y-2">
                        <div v-for="(element, index) in elements" :key="index" class="flex items-start space-x-2">
                            <select-with-other
                                class="w-full flex-1"
                                select-class="w-full form-select"
                                input-class="w-full form-input"
                                :name="`education_details[${index}][degree]`"
                                :other-name="`education_details[${index}][degree]`"
                                other-value=""
                                placeholder="Plase Specify...">

                                <option value="">---- Choose option ----- </option>
                                @foreach($degrees as $degree)
                                <option value="{{ $degree }}" :selected="element.degree === '{{ $degree }}'">{{ $degree }}</option>
                                @endforeach

                            </select-with-other>
                            <select-with-other
                                class="w-full flex-1"
                                select-class="w-full form-select"
                                input-class="w-full form-input"
                                :name="`education_details[${index}][subject]`"
                                :other-name="`education_details[${index}][subject]`"
                                other-value="-1"
                                placeholder="Plase Specify...">

                                <option value="">---- Choose option ----- </option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject }}" :selected="element.subject === '{{ $subject }}' "> {{ $subject }} </option>
                                @endforeach

                            </select-with-other>
                            <select-with-other
                                class="w-full flex-1"
                                select-class="w-full form-select"
                                input-class="w-full form-input"
                                :name="`education_details[${index}][institute]`"
                                :other-name="`education_details[${index}][institute]`"
                                other-value=""
                                placeholder="Plase Specify...">

                                <option value="">---- Choose option ----- </option>
                                @foreach($institutes as $institute)
                                <option value="{{ $institute }}" :selected="element.institute === '{{ $institute }}' "> {{ $institute }} </option>
                                @endforeach

                            </select-with-other>
                            <input type="text" :name="`education_details[${index}][year]`" v-model="element.year" class="w-24 form-input">
                            <button v-on:click.prevent="removeElement(index)" class="self-center btn is-sm text-red-600">x</button>
                        </div>
                    </div>
                </template>
            </add-remove-elements>

            <div class="space-y-4">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Broad Area of Research
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="max-w-lg">
                    <div class="mt-2 flex items-baseline">
                        <textarea id="research_area"  name="research_area" class="w-full form-textarea flex-1">{{ old('research_area', $scholar->research_area) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="btn btn-magenta">Save Changes</button>
            </div>
        </form>
    </div>
@endsection

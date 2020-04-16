@extends('layouts.scholars')
@section('body')
    <div class="container mx-auto p-4">
        <form class="bg-white p-6 h-full shadow-md" action="{{ route('scholars.profile.update') }}"
            method="POST" enctype="multipart/form-data">
            @csrf_token @method('PATCH')
            <div class="flex items-center mb-6">
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
            <div class="w-1/3">
                <div class="flex items-baseline">
                    <label for="gender" class="block form-label flex-1"> Gender</label>
                    <select id="gender" name="gender" class="block form-input flex-1">
                        <option value="" class="text-gray-600" selected>Select your gender </option>
                        @foreach ($genders as $acronym => $gender)
                        <option value=" {{ $acronym }}"
                            {{ $acronym === old("gender", $scholar->gender) ? 'selected': '' }}>
                            {{ $gender }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-baseline mt-3">
                    <label for="category" class="block form-label flex-1"> Category</label>
                    <select id="category" name="category" class="block form-input flex-1 ">
                        <option value="" class="text-gray-600" selected>Choose a category </option>
                        @foreach ($categories as $acronym => $category)
                        <option value=" {{ $acronym }}"
                            {{ $acronym === old("category", $scholar->category) ? 'selected': '' }}>
                            {{ $category }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-baseline mt-3">
                    <label for="email" class="block form-label flex-1">Email:</label>
                    <input id="email" type="email" name="email"
                        class="block form-input flex-1"
                        disabled
                        value="{{ $scholar->email }}">
                </div>
                <div class="flex items-baseline mt-3">
                    <label for="phone_no" class="block form-label flex-1">Phone Number:</label>
                    <input id="phone_no" type="text" name="phone_no" class="block form-input flex-1" value="{{ old('phone_no', $scholar->phone_no) }}">
                </div>
                <div class="flex items-baseline mt-3">
                    <label for="address" class="block form-label flex-1">Address:</label>
                    <textarea id="address" name="address" class="block w-auto form-input flex-1">{{ old('address', $scholar->address) }}</textarea>
                </div>
            </div>
            <div class="mt-6">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Admission
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="mt-4 w-1/3">
                    <div class="mt-2 flex items-baseline ">
                        <label for="enrollment_date" class="block form-label flex-1">Date of enrollment:</label>
                        <input id="enrollment_date" type="date" name="enrollment_date" class="block w-auto form-input flex-1" value="{{ old('date', $scholar->enrollment_date) }}">
                    </div>
                    <div class="flex items-baseline mt-2">
                        <label for="admission_via" class="block form-label flex-1">Admission Via:</label>
                        <select id="admission_via" name="admission_via" class="block form-input flex-1">
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
            </div>
            <div class="mt-6">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Research
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <div class="mt-4 w-1/3">
                    <div class="mt-2 flex items-baseline ">
                        <label for="research_area" class="block form-label flex-1"> Broad Area of Research: </label>
                        <textarea id="research_area"  name="research_area" class="block form-input flex-1">{{ old('research_area', $scholar->research_area) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="w-1/3 mt-6">
                <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                    <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                        Supervisor
                    </h3>
                    <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                        <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                    </svg>
                </div>
                <select name="supervisor_profile_id" id="supervisor" class="block form-input w-full">
                    <option value="" class="text-gray-600" selected> Select your supervisor </option>
                    @foreach ($supervisorProfiles as $name => $id)
                        <option value=" {{ $id }} "
                            {{ $id == old('supervisor_profile_id', $scholar->supervisor_profile_id)? 'selected':''}} >
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4">
                <add-remove-elements :existing-elements="{{ json_encode($scholar->co_supervisors) }}">
                    <template v-slot="{ elements, addElement, removeElement}">
                        <div class="flex w-2/6 items-center">
                            <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                                <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                                    Co-Supervisor(s)
                                </h3>
                                <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                                    <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                                </svg>
                            </div>
                            <button v-on:click.prevent="addElement" 
                                    v-if="elements.length < 2"
                                    class="ml-auto text-blue-700 bg-gray-300 btn is-sm"
                                > + 
                            </button>
                        </div>
                        <h6 class="mx-2 mb-6 text-gray-800 text-sm">You can add a maximum of 2 co-supervisors only.</h6>
                        <div v-for="(element, index) in elements" :key="index" class="flex items-baseline mb-2">
                            <div class="flex mt-2">
                            {{-- <label for="co_supervisors[]" class="form-label block w-1/4"> Name </label> --}}
                                <select :name="`co_supervisors[]`" 
                                    class="block form-input"
                                    >
                                    <option value=""selected> 
                                        Select your co-supervisor
                                    </option>
                                    @foreach ($cosupervisors as $name => $id)
                                        <option value=" {{ $id }} "> {{ $name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <button v-on:click.prevent="removeElement(index)"  class="btn is-sm ml-2 text-red-600">x</button>
                        </div>
                    </template>
                </add-remove-elements>
            </div>
            <div class="mt-6">
                <add-remove-elements :existing-elements="{{ json_encode($scholar->advisory_committee) }}" >
                    <template v-slot="{ elements, addElement, removeElement}">
                        <div class="flex w-2/6 items-center">
                            <div class="w-64 pr-4 relative z-10 -ml-8 my-6">
                                <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                                    Advisory Committee
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
                        <h6 class="mx-2 mb-6 text-gray-800 text-sm">You can add a maximum of 4 advisors only.</h6>
                        <div class="flex w-2/3">
                            <label for="advisory_committee[][title]" class="form-label block w-1/4">Title</label>
                            <label for="advisory_committee[][name]" class="form-label block w-1/4">Name</label>
                            <label for="advisory_committee[][designation]" class="form-label block w-1/4">Designation</label>
                            <label for="advisory_committee[][affiliation]" class="form-label block w-1/4">Affiliation</label>
                        </div>
                        <div v-for="(element, index) in elements" :key="index" class="flex items-baseline mb-2">
                            <div class="flex mt-2">
                                <input type="text" :name="`advisory_committee[${index}][title]`" v-model="element.value.title" class="block form-input mr-2">
                                <input type="text" :name="`advisory_committee[${index}][name]`" v-model="element.value.name"  class="block form-input mr-2">
                                <input type="text" :name="`advisory_committee[${index}][designation]`" v-model="element.value.designation" class="block form-input mr-2">
                                <input type="text" :name="`advisory_committee[${index}][affiliation]`" v-model="element.value.affiliation" class="block form-input">
                            </div>
                            <button v-on:click.prevent="removeElement(index)" class="btn is-sm ml-2 text-red-600">x</button>
                        </div>
                    </template>
                </add-remove-elements>
            </div>
            <div class="mt-8">
                <button type="submit" class="btn btn-magenta">Save Changes</button>
            </div>
        </form>
    </div>
@endsection
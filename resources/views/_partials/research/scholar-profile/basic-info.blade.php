<div class="page-card p-6 overflow-visible">
    <div class="-mt-6 -mx-6 bg-magenta-800 h-48 rounded-t-md flex justify-end items-end p-4">
        @if((int)auth('scholars')->id() === (int)$scholar->id)
        <a href="{{ route('scholars.profile.edit', $scholar) }}" class="btn inline-flex">
            <x-feather-icon name="edit" class="h-current mr-2"></x-feather-icon>
            Edit
        </a>
        @endif
    </div>
    <div class="-mt-24 space-y-4 text-center mb-8">
        <img src="{{ route('scholars.profile.avatar')}}"
        class="flex items-center justify-center w-48 h-48 mx-auto object-cover border-4 border-white bg-white rounded-full shadow-md overflow-hidden"
        alt="{{ $scholar->name }}'s avatar">
        <div>
            <h2 class="text-3xl">{{ $scholar->name }}</h2>
            <h3 class="text-xl text-gray-700">Scholar / {{ $scholar->research_area }}</h3>
        </div>
    </div>
    <x-tabbed-pane current-tab="info">
        <x-slot name="tabs">
            <div class="flex items-center justify-center space-x-3 border-b -mx-6 px-6">
                <x-tab name="info">Basic Info</x-tab>
                <x-tab name="admission">Admission Details</x-tab>
                <x-tab name="education">Education Details</x-tab>
            </div>
        </x-slot>

        <x-tab-content tab="info" class="flex space-x-6">
            <div class="w-64 pr-4 relative -ml-8 my-6">
                <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                    Basic Information
                </h3>
                <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                    <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                </svg>
            </div>
            <div class="mt-4 flex-1">
                <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
                    <li class="px-4 py-3 flex space-x-4">
                        <p class="whitespace-no-wrap font-bold w-48">Gender</p>
                        <p class="flex-1 text-gray-800">{{ $scholar->gender }}</p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <h4 class="whitespace-no-wrap font-bold w-48">Email</h4>
                        <p class="flex-1 text-gray-800"> {{ $scholar->email }}</p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <p class="whitespace-no-wrap font-bold w-48">Contact Number</p>
                        <p class="flex-1 text-gray-800">{{ $scholar->phone }}</p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <p class="whitespace-no-wrap font-bold w-48">Address</p>
                        <p class="flex-1 text-gray-800">
                            {{ $scholar->address ?? 'Not Known'}}
                        </p>
                    </li>
                </ul>
            </div>
        </x-tab-content>

        <x-tab-content tab="admission" class="flex space-x-6">
            <div class="w-64 pr-4 relative -ml-8 my-6">
                <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                    Admission Details
                </h3>
                <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                    <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                </svg>
            </div>
            <div class="mt-4 flex-1">
                <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
                    <li class="px-4 py-3 flex space-x-4">
                        <p class="whitespace-no-wrap font-bold w-48">Category</p>
                        <p class="flex-1 text-gray-800">{{ $scholar->category ?? 'not set' }}</p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <h4 class="whitespace-no-wrap font-bold w-48"> Date of Registration </h4>
                        <p class="flex-1 text-gray-800"> {{ optional($scholar->registration_date)->format('d F, Y') }}</p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <h4 class="whitespace-no-wrap font-bold w-48"> Enrolment Number </h4>
                        <p class="flex-1 text-gray-800"> {{ $scholar->enrolment_id ?? 'not set' }}</p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <h4 class="whitespace-no-wrap font-bold w-48"> Term Duration </h4>
                        <p class="flex-1 text-gray-800"> {{ $scholar->term_duration }} years </p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <h4 class="whitespace-no-wrap font-bold w-48"> Registration Valid Upto </h4>
                        <p class="flex-1 text-gray-800"> {{ optional($scholar->registrationValidUpto())->format('d F, Y') }} </p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <p class="whitespace-no-wrap font-bold w-48"> Admission Mode</p>
                        <p class="flex-1 text-gray-800"> {{ $scholar->admission_mode ?? '-'}}</p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <p class="whitespace-no-wrap font-bold w-48"> Funding </p>
                        <p class="flex-1 text-gray-800">
                            {{ $scholar->funding ?? '-' }}
                        </p>
                    </li>
                    <li class="px-4 py-3 flex space-x-4">
                        <p class="whitespace-no-wrap font-bold w-48">Area of Research</p>
                        <p class="flex-1 text-gray-800">
                            {{ $scholar->research_area }}
                        </p>
                    </li>
                </ul>
            </div>
        </x-tab-content>

        <x-tab-content tab="education" class="flex space-x-6">
            <div class="w-64 pr-4 relative -ml-8 my-6">
                <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                    Education Details
                </h3>
                <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                    <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                </svg>
            </div>
            <div class="flex-1 mt-4">
                <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
                    @forelse($scholar->education_details as $education)
                    <li class="py-4 px-6">
                        <p class="font-bold"> {{ $education->institute }} </p>
                        <p class="text-gray-700 truncate">
                            <span>{{ $education->degree }}</span>
                            <span class="mx-1">&bullet;</span>
                            <span>{{ $education->subject }}</span>
                        </p>
                        <p class="text-gray-700"> {{ $education->year }} </p>
                    </li>
                    @empty
                    <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No education details
                        added!</li>
                    @endforelse
                </ul>
            </div>
        </x-tab-content>
    </x-tabbed-pane>


</div>

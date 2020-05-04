<div class="page-card p-6 overflow-visible space-y-4">
    <div class="flex">
        <div class="flex items-center">
            <img src="{{ route('scholars.profile.avatar')}}" class="w-24 h-24 object-cover mr-4 border rounded shadow">
            <h3 class="text-2xl font-bold"> {{$scholar->name}}</h3>
        </div>
        <div class="ml-auto space-y-1">
            <div class="flex">
                <h4 class="font-semibold"> Gender </h4>
                <p class="ml-2"> {{ $scholar->gender }}</p>
            </div>
            <div class="flex items-center mb-1">
                <feather-icon name="at-sign" class="h-current mr-2"></feather-icon>
                <a href="mailto:{{ $scholar->email}}">{{ $scholar->email }}</a>
            </div>
            <div class="flex items-center mb-1">
                <feather-icon name="phone" class="h-current mr-2"></feather-icon>
                <a href="tel:{{ $scholar->phone_no }}">{{ $scholar->phone_no }}</a>
            </div>
            <div class="flex">
                <feather-icon name="home" class="h-current mr-2"></feather-icon>
                <address>
                    {{ $scholar->address }}
                </address>
            </div>
        </div>
    </div>

    <div class="flex space-x-6">
        <div class="w-64 pr-4 relative -ml-8 my-6">
            <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Admission
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
                    <p class="flex-1 text-gray-800"> {{ $scholar->enrollment_date }}</p>
                </li>
                <li class="px-4 py-3 flex space-x-4">
                    <p class="whitespace-no-wrap font-bold w-48"> Admission Mode</p>
                    <p class="flex-1 text-gray-800"> {{ $scholar->admission_mode ?? '-'}}</p>
                </li>
                <li class="px-4 py-3 flex space-x-4">
                    <p class="whitespace-no-wrap font-bold w-48"> Funding </p>
                    <p class="flex-1 text-gray-800">
                        {{ optional($scholar->admission_mode)->getFunding() ?? '-'}}
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
    </div>

    <div class="flex space-x-6">
        <div class="w-64 pr-4 relative -ml-8 my-6">
            <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Education
            </h3>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>
        <div class="flex-1 mt-4">
            <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
                @forelse(collect($scholar->education_details)->chunk(2) as $educationRow)
                    <li class="flex justify-between divide-x">
                        @foreach($educationRow as $education)
                        <div class="py-4 px-6 flex-1">
                            <div class="flex mb-1">
                                <feather-icon name="book" class="h-current"></feather-icon>
                                <p class="truncate">
                                    <span class="ml-2 font-bold">{{ $education->degree }}</span>
                                    <span class="ml-1 font-normal">({{ $education->subject }})</span>
                                </p>
                            </div>
                            <p class="ml-6 text-gray-700 mb-1"> {{ $education->institute }} </p>
                            <p class="ml-6 text-gray-700"> {{ $education->year }} </p>
                        </div>
                        @endforeach
                    </li>
                @empty
                    <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No education details added!</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

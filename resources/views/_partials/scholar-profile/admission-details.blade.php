@push('modals')
    <x-modal name="edit-scholar-admission-details-modal" class="p-6 w-1/2"
    :open="$errors->update->hasAny([
        'category', 'admission_mode', 'funding',
        'enrolment_id', 'registration_date', 'research_area',
    ])">
    <h2 class="text-lg font-bold mb-8">Edit Admission Details - {{ $scholar->name }}</h2>
        @include('_partials.forms.edit-scholar-admission-details', [
            'categories' => App\Types\ReservationCategory::values(),
            'admissionModes' => App\Types\AdmissionMode::values(),
            'fundings' => App\Types\FundingType::values(),
        ])
    </x-modal>
@endpush
<div>
    <div class="w-64 pr-4 relative -ml-8 my-6">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Admission Details
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <div class="mt-4">
        @can('updateProfile', [App\Models\Scholar::class, $scholar])
        <x-modal.trigger modal="edit-scholar-admission-details-modal"
            class="p-1 text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
            <x-feather-icon class="h-5" name="edit-3">Edit</x-feather-icon>
        </x-modal.trigger>
        @endcan
    </div>
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
@extends('layouts.master', ['pageTitle' => 'Profile'])
@push('modals')
    <x-modal name="edit-scholar-basic-info-modal" class="p-6 w-1/2"
        :open="$errors->update->hasAny(['phone', 'address', 'gender'])">
        <h2 class="text-lg font-bold mb-8">Edit Basic Info - {{ $scholar->name }}</h2>
            @include('_partials.forms.edit-scholar-basic-info' ,[
                'genders' => App\Types\Gender::values(),
            ])
    </x-modal>
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
@section('body')
    <div class="container mx-auto p-4 space-y-8">
        @include('_partials.research.scholar-profile.basic-info')
        @include('_partials.research.scholar-profile.supervisors-card')
        @include('_partials.research.scholar-profile.cosupervisors-card')
        {{-- @include('_partials.research.scholar-profile.advisory-committee') --}}
        {{-- @include('_partials.research.scholar-profile.pre-phd-courseworks') --}}
        @include('_partials.research.scholar-profile.publications')
        @include('_partials.research.scholar-profile.presentations')
        @include('_partials.research.scholar-profile.leaves')
        @include('_partials.research.scholar-profile.advisory-meetings')
        @include('_partials.research.scholar-profile.progress-reports')
        @include('_partials.research.scholar-profile.documents')
        @include('_partials.research.scholar-profile.pre-phd-seminar')
        @include('_partials.research.scholar-profile.title-approval')
        @include('_partials.research.scholar-profile.examiner')
@endsection

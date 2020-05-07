@extends('layouts.scholars', ['pageTitle' => 'Dashboard'])
@section('body')
<div class="grid grid-cols-10 gap-4">
    <div class="page-card p-6 col-span-10">
        <h2 class="text-3xl">Hello {{ optional(auth()->user())->first_name ?? 'Unknown' }}!</h2>
    </div>

    {{-- Coursework Scorecard --}}
    <x-dashboard-scorecard class="relative col-span-2" icon="book-open" label="Coursework" href="#">
        <span class="text-3xl">{{ $scholar->completed_courseworks_count }}</span>
        <span>/</span>
        <span>{{ $scholar->courseworks_count }}</span>
    </x-dashboard-scorecard>

    {{-- Publication Scorecard --}}
    <x-dashboard-scorecard class="relative col-span-2" icon="file" label="Publication" href="#">
        <span class="text-3xl">{{ $scholar->journals_count + $scholar->conferences_count }}</span>
    </x-dashboard-scorecard>

    {{-- Presentations Scorecard --}}
    <x-dashboard-scorecard class="relative col-span-2" icon="monitor" label="Presentations" href="#">
        <span class="text-3xl">{{ $scholar->presentations_count }}</span>
    </x-dashboard-scorecard>

    {{-- Advisory Meeting Scorecard --}}
    <x-dashboard-scorecard class="relative col-span-2" icon="briefcase" label="Meetings" href="#">
        <span class="text-3xl">{{ $scholar->advisory_meetings_count }}</span>
    </x-dashboard-scorecard>

    {{-- Advisory Meeting Scorecard --}}
    <x-dashboard-scorecard class="relative col-span-2" icon="umbrella" label="Leaves" href="#">
        <span class="text-3xl">{{ $scholar->approved_leaves_count }}</span>
        <span>/</span>
        <span>{{ $scholar->leaves_count }}</span>
    </x-dashboard-scorecard>
</div>
@endsection

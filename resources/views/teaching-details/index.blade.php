@extends('layouts.master')
@section('body')
@include('_partials.user-profile.current-teaching-detail')
<div class="page-card p-6">
    <div class="border-b -mx-6 px-6 -mt-6 pt-4 pb-3 mb-3">
        <h4 class="text-xl font-bold flex items-center">
            <x-feather-icon name="git-commit" class="h-current mr-4"></x-feather-icon>
            <span>Teaching History</span>
        </h4>
    </div>
    @forelse ($oldTeachingRecords as $date => $records)
        <x-timeline-item icon="circle" color="text-gray-400">
            <h4 class="font-bold mb-3">{{ $date }}</h4>
            <ul class="border rounded divide-y">
                @foreach($records as $record)
                <li class="px-4 py-2">
                    Taught <b>{{ $record->course->name }} <em>({{ $record->course->code }})</em></b>
                    under {{ $record->programmeRevision->programme->name }}
                    (w.e.f {{ $record->programmeRevision->revised_at->year }})
                    in <em class="underline">{{ $record->college->name }}</em> as
                    <strong>{{ $record->designation }}</strong> teacher.
                </li>
                @endforeach
            </ul>
        </x-timeline-item>
    @empty
        <div class="mt-6 text-gray-600 font-bold"> Nothing to see here. </div>
    @endforelse
</div>
@endsection

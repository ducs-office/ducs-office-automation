@push('modals')
    <x-modal name="add-scholar-coursework-modal" class="p-6 w-1/2"
        :open="!$errors->addCoursework->isEmpty()">
        <h2 class="text-lg font-bold mb-8"> Add Coursework - {{ $scholar->name }}</h2>
            @include('_partials.forms.add-scholar-coursework' ,[
                'courses' => $courses,
            ])
    </x-modal>
    <livewire:mark-scholar-coursework-completed-modal :error-bag="$errors->updateCoursework" :scholar="$scholar" />
@endpush
<div class="page-card p-6 overflow-visible">
    <div class="-mx-6 -mt-6 px-6 py-3 border-b mb-6 flex items-center">
        <div class="flex items-center space-x-2">
            <x-feather-icon name="book" class="h-4 w-4"></x-feather-icon>
            <h2 class="text-lg font-bold">Pre PhD Courseworks</h2>
        </div>
        @can('create', [App\Models\Pivot\ScholarCoursework::class, $scholar])
            <x-modal.trigger modal="add-scholar-coursework-modal"
                class="ml-auto inline-flex items-center space-x-1 btn btn-magenta is-sm">
                <x-feather-icon name="plus" class="w-4 h-4"></x-feather-icon>
                <span>Add</span>
            </x-modal.trigger>
        @endcan
    </div>
    <ul class="border rounded-lg overflow-hidden mb-4">
        @foreach ($scholar->courseworks as $course)
            @include('_partials.list-items.pre-phd-coursework')
        @endforeach
    </ul>
</div>

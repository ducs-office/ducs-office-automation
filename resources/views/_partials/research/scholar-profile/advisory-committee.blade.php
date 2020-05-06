<div class="page-card p-6 overflow-visible flex space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-6">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Advisory Committee
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
        <div class="flex justify-center mt-4">
            @can('scholars.advisory_committee.manage', $scholar)
            @include('research.scholars.modals.edit_advisory_committee', [
                'modalName' => 'edit-advisory-committee-modal',
                'existingCosupervisors' => $existingCosupervisors,
                'scholar' => $scholar
            ])
            <button class="btn btn-magenta is-sm shadow-inner ml-auto mr-2" @click.prevent="$modal.show('edit-advisory-committee-modal', {
                actionUrl: '{{ route('research.scholars.advisory_committee.update', $scholar) }}',
                actionName: 'Update'
            })">
                Edit
            </button>
            <button class="btn btn-magenta is-sm shadow-inner" @click.prevent="$modal.show('edit-advisory-committee-modal', {
                actionUrl: '{{ route('research.scholars.advisory_committee.replace', $scholar) }}',
                actionName: 'Replace'
            })">
                Replace
            </button>
            @endcan
        </div>
    </div>
    <div class="flex-1">
        @include('research.scholars.partials.advisory_committee',[
            'advisoryCommittee' => $scholar->advisory_committee
        ])
        @foreach ($scholar->old_advisory_committees as $oldCommittee)
        <details class="mt-1">
            <summary class="p-2 bg-gray-200 rounded">
                {{ $oldCommittee['from_date']->format('d F Y') }} - {{ $oldCommittee['to_date']->format('d F Y') }}
            </summary>
            <div class="ml-2 pl-4 py-2 border-l">
                @include('research.scholars.partials.advisory_committee',[
                'advisoryCommittee' => $oldCommittee['committee']
                ])
            </div>
        </details>
        @endforeach
    </div>
</div>

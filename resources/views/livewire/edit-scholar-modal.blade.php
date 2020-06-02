<x-modal name="{{ $modalName }}" class="min-w-1/3 p-6" :open="$showModal">
    <h2 class="text-lg font-bold mb-8">Update Scholar</h2>
    @if($showModal)
        @include('_partials.forms.edit-scholar', [
            'supervisors' => $supervisors,
            'cosuperviors' => $cosupervisors,
        ])
    @else
        <span>Loading...</span>
    @endif
</x-modal>

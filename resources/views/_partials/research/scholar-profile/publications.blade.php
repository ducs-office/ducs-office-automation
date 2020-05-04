{{-- Publications --}}
<div class="page-card p-6 overflow-visible space-y-6">
    @include('_partials.research.journal-publications', [
        'journals' => $scholar->journals
    ])

    @include('_partials.research.conference-publications', [
        'conferences' => $scholar->conferences
    ])
</div>

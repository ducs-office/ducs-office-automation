<button {{ $attributes }}
    x-on:click="
        @if($attributes->get('livewire', null) !== null)
            $modal.showLivewire('{{ $attributes['modal'] }}', 'show', {{ $attributes->get('livewire') }})
        @else
            $modal.show('{{ $attributes['modal'] }}')
        @endif
    ">
    {{ $slot }}
</button>

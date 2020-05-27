@props(['modal', 'livewire' => null])
<button {{ $attributes }}
    x-on:click="
        $modals.open('{{ $modal }}', {{ json_encode($livewire) }})
    ">
    {{ $slot }}
</button>

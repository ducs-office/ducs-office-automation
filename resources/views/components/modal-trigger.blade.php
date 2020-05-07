<button {{ $attributes }} x-on:click="$modal.show('{{ $attributes['name'] }}')">
    {{ $slot }}
</button>

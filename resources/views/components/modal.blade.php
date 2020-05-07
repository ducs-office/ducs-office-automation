<template x-if="$modal.isOpen('{{ $attributes['name'] }}')">
    <div class="fixed inset-0 bg-black-40 p-6 flex items-center justify-center z-50">
        <div x-on:click.away="$modal.hide()"
            {{ $attributes->merge(['class' => "bg-white rounded shadow-lg"]) }}>
            {{ $slot }}
        </div>
    </div>
</template>

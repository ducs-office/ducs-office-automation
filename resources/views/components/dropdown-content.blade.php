<template x-if="{{ $attributes->get('dropdown', '$dropdown') }}.isOpen">
    <div class="relative">
        {{-- <div class="fixed inset-0 bg-black-20"></div> --}}
        <div x-on:click.away="{{ $attributes->get('dropdown', '$dropdown') }}.toggle()"
            {{ $attributes->except('dropdown')->merge(['class' => "absolute right-0 z-50"]) }}>
            {{ $slot }}
        </div>
    </div>
</template>

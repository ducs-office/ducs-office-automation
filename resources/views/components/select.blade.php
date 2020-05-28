@props([
    'name' => '',
    'placeholder' => 'Select an item',
    'searchPlaceholder' => 'Search an item',
    'queryModel' => 'searchQuery',
])
<div x-data="CustomSelect()" x-init="init()"
    {{ $attributes->merge(['class' => 'relative w-full']) }}>
    <button type="button"
        class="text-left form-select w-full"
        x-on:click.prevent="toggle()">
        <span x-show="isEmpty()" class="opacity-50">{{ $placeholder }}</span>
        <span x-show="! isEmpty()" x-html="selectedOptionHTML"></span>
    </button>
    <template x-if="! isEmpty()">
        <input type="hidden" name="{{ $name }}" x-model="selectedValue">
    </template>
    <div x-show="opened" x-on:click.away="close()"
        class="absolute z-20 page-card border shadow-lg rounded p-4 w-full inset-x-0 mt-1">
        <input x-ref="input" type="text"
            placeholder="{{ $searchPlaceholder }}"
            class="w-full form-input mb-4"
            wire:model="{{ $queryModel }}"
            x-on:input.stop
            x-on:keydown.arrow-down="highlightNext()"
            x-on:keydown.arrow-up="highlightPrev()"
            x-on:keydown.enter.prevent="selectHighlighted()"
            x-on:keydown.escape.prevent="close()">
        <div x-ref="dom" wire:ignore class="-mx-4 max-h-48 overflow-y-auto"></div>
        <div x-ref="options" x-show="false">
            {{ $slot }}
        </div>
    </div>
</div>

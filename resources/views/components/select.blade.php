@props([
    'multiple' => false,
    'name' => '',
    'placeholder' => 'Select an item',
    'searchPlaceholder' => 'Search an item',
    'queryModel' => 'searchQuery',
])
<div x-data="CustomSelect({multiple: {{ json_encode($multiple) }}})" x-init="init()"
    {{ $attributes->merge(['class' => 'relative w-full']) }}>
    <button type="button"
        class="text-left form-select w-full"
        x-on:click.prevent="toggle()">
        <span x-show="isEmpty()" class="opacity-50">{{ $placeholder }}</span>
        @if(! $multiple)
            <span x-show="! isEmpty()" x-html="selectedOptionHTML"></span>
        @else
        <template x-for="(optionHTML,index) in selectedOptionHTMLs">
            <div x-key="index" class="inline-flex px-2 py-1 space-x-1 bg-magenta-700 text-white text-sm rounded">
                <span x-html="optionHTML"></span>
                <button class="p-1" x-on:click="deselect(index)">
                    <x-feather-icon name="x" class="h-current"></x-feather-icon>
                </button>
            </div>
        </template>
        @endif
    </button>
    @if(! $multiple)
    <template x-if="! isEmpty()">
        <input type="hidden" name="{{ $name }}" x-model="selectedValue">
    </template>
    @else
    <template x-if="! isEmpty()" x-for="value in selectedValue">
        <input type="hidden" name="{{ $name }}" x-model="value">
    </template>
    @endif
    <div x-show="opened" x-on:click.away="close()"
        class="absolute z-20 page-card border shadow-lg rounded p-4 w-full inset-x-0 mt-1">
        <input x-ref="input" type="text"
            placeholder="{{ $searchPlaceholder }}"
            class="w-full form-input mb-4"
            wire:model="{{ $queryModel }}"
            x-on:input.stop
            @if($multiple)
            x-on:keydown.backspace="onBackspace()"
            @endif
            x-on:keydown.arrow-down="highlightNext()"
            x-on:keydown.arrow-up="highlightPrev()"
            @if(!$multiple)
            x-on:keydown.enter.prevent="selectHighlighted()"
            @else
            x-on:keydown.enter.prevent="toggleHighlighted()"
            @endif
            x-on:keydown.escape.prevent="close()">
        <div x-ref="dom" wire:ignore class="-mx-4 max-h-48 overflow-y-auto"></div>
        <div x-ref="options" x-show="false">
            {{ $slot }}
        </div>
    </div>
</div>

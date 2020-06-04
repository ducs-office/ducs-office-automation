@props([
    'multiple' => false,
    'name' => '',
    'value' => null,
    'placeholder' => 'Select an item',
])
<div x-data="CustomSelect({
    multiple: {{ json_encode($multiple) }}
})" x-init="init({{ json_encode($value) }})"
    x-model="selectedValue"
    x-on:focusout="close()"
    @if($multiple)
    x-on:keydown.backspace="onBackspace()"
    x-on:keydown.enter.prevent.stop="opened ? toggleHighlighted() : open()"
    @else
    x-on:keydown.enter.prevent.stop="opened ? selectHighlighted() : open()"
    @endif
    x-on:keydown.arrow-down.prevent.stop="highlightNext()"
    x-on:keydown.arrow-up.prevent.stop="highlightPrev()"
    x-on:keydown.escape.prevent.stop="close()"
    {{ $attributes->merge(['class' => 'relative w-full']) }}>
    <button type="button"
        x-on:click="toggle()"
        class="text-left form-select w-full">
        <span x-show="isEmpty()" class="opacity-50">{{ $placeholder }}</span>
        @if(! $multiple)
            <span x-show="! isEmpty()" x-html="selectedOptionHTML"></span>
        @else
        <div class="flex flex-wrap">
            <template x-for="(optionHTML,index) in selectedOptionHTMLs">
                <div x-key="index" class="inline-flex px-2 py-1 leading-none space-x-1 bg-magenta-700 text-white text-sm rounded-full m-1">
                    <span x-html="optionHTML"></span>
                    <button x-on:click.stop.prevent="deselect(index)">
                        <x-feather-icon name="x" class="h-current"></x-feather-icon>
                    </button>
                </div>
            </template>
        </div>
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
        class="absolute z-20 page-card p-0 border shadow-lg rounded w-full inset-x-0 mt-1">
        @isset($query) {{ $query }} @endisset
        <ul tabindex="-1" x-ref="dom" wire:ignore class="py-2 max-h-64 overflow-y-auto"></ul>
    </div>
    <div x-ref="options" x-show="false">
        {{ $slot }}
    </div>
</div>

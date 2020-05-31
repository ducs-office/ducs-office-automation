@props([
    'multiple' => false,
    'name' => '',
    'value' => null,
    'placeholder' => 'Select an item',
])
<div x-data="CustomSelect({
    multiple: {{ json_encode($multiple) }}
})" x-init="init({{ json_encode($value) }})"
    {{-- wait for click to be fired first, incase focus received due to click, so that there is no double toggle --}}
    x-on:focusin="setTimeout(() => open(), 150)"
    x-on:focusout="close()"
    @if($multiple)
    x-on:keydown.backspace="onBackspace()"
    x-on:keydown.enter.prevent="opened ? toggleHighlighted() : open()"
    @else
    x-on:keydown.enter.prevent="opened ? selectHighlighted() : open()"
    @endif
    x-on:keydown.arrow-down="highlightNext()"
    x-on:keydown.arrow-up="highlightPrev()"
    x-on:keydown.escape.prevent="close()"
    {{ $attributes->merge(['class' => 'relative w-full']) }}>
    <button type="button"
        x-on:click="toggle()"
        class="text-left form-select w-full">
        <span x-show="isEmpty()" class="opacity-50">{{ $placeholder }}</span>
        @if(! $multiple)
            <span x-show="! isEmpty()" x-html="selectedOptionHTML"></span>
        @else
        <template x-for="(optionHTML,index) in selectedOptionHTMLs">
            <div x-key="index" class="inline-flex px-2 py-1 leading-none space-x-1 bg-magenta-700 text-white text-sm rounded-full">
                <span x-html="optionHTML"></span>
                <button x-on:click.stop.prevent="deselect(index)">
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
        x-ref="dom" wire:ignore
        class="absolute z-20 page-card px-0 py-2 border shadow-lg rounded p-4 w-full inset-x-0 mt-1 min-h-64 overflow-y-auto">
    </div>
    <div x-ref="options" x-show="false">
        {{ $slot }}
    </div>
</div>

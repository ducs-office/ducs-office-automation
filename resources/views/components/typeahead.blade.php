@props([
'multiple' => false,
'name' => '',
'open' => false,
'value' => null,
'valueKey' => 'id',
'choices' => [],
'disabledChoices' => [],
'choicesRef' => 'listbox',
'placeholder' => 'Select Item',
])
<div x-data="CustomSelect({
    multiple: {{ json_encode($multiple) }},
    value: {{ json_encode($value) }},
    valueKey: {{ json_encode($valueKey) }},
    choices: {{ json_encode($choices) }},
    disabledChoices: {{ json_encode($disabledChoices) }},
    choicesRef: {{ json_encode($choicesRef) }},
    open: {{ json_encode($open) }},
})" x-init="init()" {{ $attributes->merge(['class' => 'relative w-full']) }}>
    <button type="button" x-ref="button" x-on:click="onButtonClick()" class="text-left form-select w-full">
        <div x-show="isEmpty()"><span class="opacity-50">{{ $placeholder }}</span></div>
        <div x-show="! isEmpty()" class="flex flex-wrap">
            <template x-for="(selectedChoice, index) in selectedChoices" x-bind:key="index">
                @if(!$multiple)
                @isset($selectedChoice)
                {{ $selectedChoice }}
                @else
                <span x-text="selectedChoice[valueKey]"></span>
                @endisset
                @else
                <div class="inline-flex px-2 py-1 leading-0 space-x-1 bg-gray-200 text-sm rounded-lg m-1">
                    @isset($selectedChoice)
                    {{ $selectedChoice }}
                    @else
                    <span x-text="selectedChoice[valueKey]"></span>
                    @endisset
                    <button x-on:click.stop.prevent="deselect(index)" tabindex="-1">
                        <x-feather-icon name="x" class="h-current"></x-feather-icon>
                    </button>
                </div>
                @endif
            </template>
        </div>
    </button>
    @if(! $multiple)
    <template x-if="! isEmpty()">
        <input type="hidden" name="{{ $name }}" x-bind:value="value">
    </template>
    @else
    <template x-if="! isEmpty()" x-for="singleValue in value">
        <input type="hidden" name="{{ $name }}" x-bind:value="singleValue">
    </template>
    @endif
    <div x-show="open" x-on:click.away="open = false"
        class="absolute my-1 z-20 bg-white border shadow-lg rounded w-full">
        @isset($query)
        {{ $query }}
        @endisset
        <ul tabindex="-1" x-ref="listbox" class="py-2 max-h-64 overflow-y-auto focus:outline-none"
            x-on:keydown.escape="onEscape()" x-on:keydown.enter.prevent.stop="onOptionSelected()"
            x-on:keydown.arrow-down.prevent.stop="highlightNext()" x-on:keydown.arrow-up.prevent.stop="highlightPrev()">
            @isset($slot)
            {{ $slot }}
            @else
            <template x-for="(choice, index) in choices" x-bind:key="index">
                <span x-text="choice[valueKey]"></span>
            </template>
            @endisset
        </ul>
    </div>
</div>

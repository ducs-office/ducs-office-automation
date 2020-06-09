<div x-data="CustomSelect({
    open: {{ json_encode($open) }},
    value: {{ json_encode($value) }},
    valueKey: 'id',
    choices: {{ json_encode($colleges) }},
    disabledChoices: [],
    choicesRef: 'listbox',
    inputRef: 'input',
})" x-init="init()" class="relative w-full">
    <button type="button" x-ref="button" x-on:click="onButtonClick()" class="text-left form-select w-full">
        <div x-show="isEmpty()"><span class="opacity-50">{{ $placeholder }}</span></div>
        <template x-if="! isEmpty()">
            <div class="flex flex-wrap -m-1">
                <template x-for="(selectedChoice, index) in selectedChoices" x-bind:key="index">
                    <div class="flex space-x-2 m-1">
                        <span x-text="selectedChoice.name"></span>
                    </div>
                </template>
            </div>
        </template>
    </button>
   
    <template x-if="! isEmpty()">
        <input type="hidden" name="{{ $name }}" x-bind:value="value">
    </template>
    
    <div x-show="open" x-on:click.away="open = false"
        class="absolute my-1 z-20 bg-white border shadow-lg rounded w-full">
        <div class="p-2">
            <input x-ref="input" type="text" wire:model="query" class="w-full form-input"
                placeholder="{{ $searchPlaceholder }}" x-on:keydown.escape="onEscape()"
                x-on:keydown.enter.prevent.stop="onOptionSelected()"
                x-on:keydown.arrow-down.prevent.stop="highlightNext()"
                x-on:keydown.arrow-up.prevent.stop="highlightPrev()">
        </div>
        <ul tabindex="-1" x-ref="listbox" class="py-2 max-h-64 overflow-y-auto focus:outline-none">
            @foreach($colleges as $index => $college)
            <x-select.option class="px-4 py-2" :index="$index" value="{{ $college->id }}">
                <div class="flex space-x-2">
                    <span>[{{ $college->code }}] {{ $college->name }}</span>
                </div>
            </x-select.option>
            @endforeach
        </ul>
    </div>
</div>

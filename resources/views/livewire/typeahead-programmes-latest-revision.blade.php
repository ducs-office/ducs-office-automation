<div x-data="CustomSelect({
    open: {{ json_encode($open) }},
    multiple: {{ json_encode($multiple) }},
    value: {{ json_encode($value) }},
    valueKey: 'id',
    choices: {{ json_encode($programmes) }},
    disabledChoices: [],
    choicesRef: 'listbox',
    inputRef: 'input',
})" x-init="init()" class="relative w-full"
    x-on:input="window.livewire.emit('programmeRevisionSelected', $event.target.value)">
    <button type="button" x-ref="button" x-on:click="onButtonClick()" class="text-left form-select w-full">
        <div x-show="isEmpty()"><span class="opacity-50">{{ $placeholder }}</span></div>
        <template x-if="! isEmpty()">
            <div class="flex flex-wrap -m-1">
                <template x-for="(selectedChoice, index) in selectedChoices" x-bind:key="index">
                    @if(!$multiple)
                        <div class="m-1" x-text="selectedChoice.name"></div>
                    @else
                    <div class="inline-flex items-center px-2 py-1 space-x-2 bg-gray-200 text-sm rounded-full m-1">
                        <span x-text="selectedChoice.name"></span>
                        <button x-on:click.stop.prevent="deselect(index)" tabindex="-1">
                            <x-feather-icon name="x" class="h-current"></x-feather-icon>
                        </button>
                    </div>
                    @endif
                </template>
            </div>
        </template>
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
        <div class="p-2">
            <input x-ref="input" type="text" wire:model="query" class="w-full form-input"
                placeholder="{{ $searchPlaceholder }}" x-on:keydown.escape="onEscape()"
                x-on:keydown.enter.prevent.stop="onOptionSelected()"
                x-on:keydown.arrow-down.prevent.stop="highlightNext()"
                x-on:keydown.arrow-up.prevent.stop="highlightPrev()">
        </div>
        <ul tabindex="-1" x-ref="listbox" class="py-2 max-h-64 overflow-y-auto focus:outline-none">
            @foreach($programmes as $index => $programme)
            <x-select.option class="px-4 py-2" :index="$index" value="{{ $programme->latest_revision_id }}">
                <div class="flex space-x-2">
                    <span class="bg-gray-200 text-gray-900 px-4 py-1 text-sm rounded-full">{{ $programme->code }}</span>
                    <span>{{ $programme->name }}</span>
                </div>
            </x-select.option>
            @endforeach
        </ul>
    </div>
</div>

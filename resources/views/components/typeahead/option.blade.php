@props(['value' => '', 'index'])
<li {{ $attributes }}
    x-bind:class="{
        'cursor-not-allowed opacity-50': isDisabled({{ $index }}),
        'cursor-pointer': ! isDisabled({{ $index }}),
        'bg-magenta-700 text-white': isHighlighted({{ $index }}),
        'bg-gray-100 font-bold': isSelected('{{ $value }}'),
    }"
    x-on:mouseover="highlight({{ $index }})"
    x-on:click.prevent="onOptionSelected()">
    <div class="flex items-center">
        {{ $slot }}
        <template x-if="isDisabled({{ $index }})">
            <x-feather-icon name="x-circle" class="ml-auto h-current flex-shrink-0"></x-feather-icon>
        </template>
        <template x-if="isSelected('{{ $value }}')">
            <x-feather-icon name="check" class="ml-auto h-current flex-shrink-0"></x-feather-icon>
        </template>
    </div>
</li>

@props([
    'id',
    'placeholder' => 'Select file...',
    'class' => '',
    'icon' => 'upload',
])
<button type="button" x-data="{filenames: []}" for="{{ $id }}" tabindex="0" class="{{ $class }}" x-on:click.stop="$refs.input.click()">
    <input x-ref="input" tabindex="-1"
        id="{{ $id }}" type="file" class="absolute h-0 w-0"
        {{ $attributes }} x-on:input="filenames = Array.from($event.target.files).map(f => f.name);">
    <div class="flex flex-wrap items-center">
        <x-feather-icon :name="$icon" class="h-current mr-2"></x-feather-icon>
        <span class="opacity-50" x-show="filenames.length == 0">{{ $placeholder }} (max: 200 kB)</span>
        <template x-for="name in filenames">
            <span x-text="name" class="mx-1 inline-block underline truncate"></span>
        </template>
    </div>
</button>

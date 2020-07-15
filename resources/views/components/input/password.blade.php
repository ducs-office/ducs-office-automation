@props([
    'name' => '',
    'placeholder' => '',
    'required' => true,
])
<div x-data="{ type: 'password' }"
    {{ $attributes->merge(['class' => 'flex items-center space-x-1']) }}>
    <input x-bind:type="type" name="{{ $name }}" class="flex-1 outline-none focus:outline-none" placeholder="{{ $placeholder }}" requried="{{ json_encode($required) }}">
    <button type="button" x-on:click="type === 'password' ? type = 'text' : type = 'password'"
        class="p-1 transform transition-transform duration-100 hover:scale-105">
        <template x-if="type === 'password'">
            <x-feather-icon class="h-current" name="eye"></x-feather-icon>
        </template>
        <template x-if="type !== 'password'">
            <x-feather-icon class="h-current" name="eye-off"></x-feather-icon>
        </template>
    </button>
</div>
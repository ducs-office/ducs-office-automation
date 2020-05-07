<div x-show="$tabs.isActive('{{ $attributes->get('tab', '') }}')"
    key="{{ $attributes->get('tab', '') }}"
    x-transition:enter="transition linear duration-200 delay-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition linear duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    {{ $attributes->except('tab')->merge([
        'class' => 'row-start-1 col-start-1'
    ]) }}>
    {{ $slot }}
</div>

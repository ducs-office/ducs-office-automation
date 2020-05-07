<div {{ $attributes->except('current-tab') }}
    x-data="tabbedPane('{{ $attributes->get('current-tab') }}')">
    {{ $tabs }}
    <div class="grid grid-cols-1">
        {{ $slot }}
    </div>
</div>

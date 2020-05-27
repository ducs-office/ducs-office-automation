<div {{ $attributes->except('name')->merge(['class' => "ml-auto mr-3"]) }}
    x-data="dropdown('{{ $attributes->get('name', '$dropdown') }}')">
    {{ $slot }}
</div>

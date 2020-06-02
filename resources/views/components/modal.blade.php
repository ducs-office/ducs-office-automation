<div x-data="modal('{{ $attributes->get('name') }}', {{ $attributes->get('open', false) ? 'true' : 'false' }})"
    x-on:openmodal.window="handleOpenEvent($event)"
    x-on:closemodal.window="handleCloseEvent($event)"
    x-show="isOpen"
    class="fixed inset-0 bg-black-40 p-6 flex items-center justify-center z-50 overflow-y-auto">
    <div x-on:click.away="close()"
        {{ $attributes->except('name', 'open')->merge(['class' => "bg-white rounded shadow-lg"]) }}>
        {{ $slot }}
    </div>
</div>

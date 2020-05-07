<button x-on:click="$tabs.switchTo('{{ $attributes->get('name', '') }}')"
    {{
        $attributes->except('name')->merge([
            'class' => "relative px-3 py-2 border border-b-0 rounded-t
            bg-white overflow-hidden
            hover:text-magenta-700 focus:text-magenta-700 hover:underline focus:underline focus:outline-none ",
            'type' => 'button'
        ])
    }}
    x-bind:class="{'-mb-px': $tabs.isActive('{{ $attributes->get('name') }}')}" >
    <div x-show="$tabs.isActive('{{ $attributes->get('name') }}')"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform scale-x-0"
        x-transition:enter-end="transform scale-x-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="transform scale-x-100"
        x-transition:leave-end="transform scale-x-0"
        class="absolute top-0 inset-x-0 h-0 rounded-t border-t-4 border-magenta-700"></div>
    {{ $slot }}
</button>

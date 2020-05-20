<button {{ $attributes->except('dropdown')->merge(['class' => "relative z-20"]) }}
    x-on:click="{{$attributes->get('dropdown', '$dropdown')}}.toggle()">
    {{ $slot }}
</button>

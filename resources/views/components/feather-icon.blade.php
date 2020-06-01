<svg x-data="{iconHTML: '', name: null}"
    x-init="iconHTML = featherIcon(name || '{{ $attributes['name'] }}')"
    viewBox="0 0 24 24" {{ $attributes->merge([
        'stroke-width' => '2',
        'fill' => 'none',
        'stroke' => 'currentColor',
        'stroke-linecap' => 'round',
        'stroke-linejoin' => 'round',
    ]) }} >
    <title>{{ $slot }}</title>
    <g x-html="iconHTML"></g>
</svg>

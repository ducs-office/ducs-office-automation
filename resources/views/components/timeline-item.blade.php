<div class="relative py-4 pl-8">
    <svg viewBox="0 0 10 10" preserveAspectRatio="none"
        class="absolute inset-y-0 left-0 transform -translate-x-1/2 w-1 {{ $attributes->get('color', 'text-gray-300') }} h-full text-gray-300">
        <rect x="0" y="0" width="10" height="10" fill="currentColor"></rect>
    </svg>
    {{-- <div class="absolute  w-1 bg-gray-300 inset-y-0 left-0"></div> --}}
    <div class="absolute mt-3 left-0 top-0 transform -translate-x-1/2">
        @isset($bullet)
            {{ $bullet }}
        @else
        <x-feather-icon name="{{ $attributes->get('icon', 'circle') }}" fill="#fff" stroke-width="2" class="h-8 {{ $attributes->get('color', 'text-gray-300') }}"></x-feather-icon>
        @endisset
    </div>
    <div>
        {{ $slot }}
    </div>
</div>

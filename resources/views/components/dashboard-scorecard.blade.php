<div {{ $attributes->merge(['class' => 'page-card p-4']) }}>
    <div class="flex items-center">
        <a href="{{ $attributes->get('href', '#') }}" class="transform transition-transform duration-100 hover:scale-110 text-magenta-600 mr-4">
            <x-feather-icon name="{{ $attributes->get('icon', 'x') }}" stroke-width="1.5" class="flex-shrink-0 w-10"></x-feather-icon>
        </a>
        <div>
            <div class="leading-none mb-1">
                {{ $slot }}
            </div>
            <h4 class="text-base text-gray-700 font-semibold tracking-wider">{{ $attributes->get('label', 'Score') }}</h4>
        </div>
    </div>
</div>

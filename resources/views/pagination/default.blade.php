@if ($paginator->hasPages())
<nav class="flex justify-between items-baseline">
    <ul class="flex justify-center bg-white rounded-lg shadow-md overflow-hidden">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span class="inline-block bg-gray-100 py-2 px-3" aria-hidden="true">@lang('pagination.previous')</span>
        </li>
        @else
        <li>
            <a class="inline-block py-2 px-3 hover:border-magenta-700 hover:bg-magenta-700 hover:text-white"
                href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                @lang('pagination.previous')
            </a>
        </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <li aria-disabled="true">
            <span class="inline-block px-4">{{ $element }}</span>
        </li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li aria-current="page"><span class="inline-block py-2 px-3 bg-gray-100 font-bold">{{ $page }}</span></li>
        @else
        <li><a class="inline-block py-2 px-3 hover:border-magenta-700 hover:bg-magenta-700 hover:text-white"
                href="{{ $url }}">{{ $page }}</a></li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <li>
            <a class="inline-block py-2 px-3 hover:border-magenta-700 hover:bg-magenta-700 hover:text-white"
                href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                @lang('pagination.next')
            </a>
        </li>
        @else
        <li aria-disabled="true" aria-label="@lang('pagination.next')">
            <span class="inline-block bg-gray-100 py-2 px-3 " aria-hidden="true">@lang('pagination.next')</span>
        </li>
        @endif
    </ul>
    <p class="mr-4">
        Displaying <b>{{ $paginator->firstItem() }}</b> - <b>{{ $paginator->lastItem() }}</b> out of
        <b>{{ $paginator->total() }}</b>
    </p>
</nav>
@endif

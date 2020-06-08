<div x-data="{ isOpen: false }" class="relative ml-auto">
    <div class="flex items-center space-x-2">
        <button class="group btn btn-magenta is-sm inline-flex items-center space-x-2 ml-auto" x-on:click="isOpen = !isOpen">
            <x-feather-icon name="filter" class="h-current transform group-hover:scale-110 transition-transform duration-150" stroke-width="2"></x-feather-icon>
            <span>Add Filters</span>
        </button>
        <a href="{{ request()->url() }}" class="group btn hover:bg-gray-300 is-sm inline-flex items-center space-x-2 ml-auto">
            <x-feather-icon name="x-circle" class="h-current transform group-hover:scale-110 transition-transform duration-150"
                stroke-width="2"></x-feather-icon>
            <span>Clear Filters</span>
        </a>
    </div>
    <form method="GET" x-show="isOpen"
        x-on:submit.prevent="
            window.location.search = Array.from($event.target.elements)
                .filter(el => el.value != '')
                .map(el => encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value))
                .join('&')
        "
        class="fixed z-20 inset-y-0 right-0 border max-w-md shadow-lg overflow-y-auto bg-white p-4 z-10"
        x-on:click.away="isOpen = false"
        x-transition:enter="transition-transform duration-150"
        x-transition:enter-start="transform translate-x-full opacity-0"
        x-transition:enter-end="transform translate-x-0 opacity-1"
        x-transition:leave="transition duration-150"
        x-transition:leave-start="transform translate-x-0 opacity-1"
        x-transition:leave-end="transform translate-x-full opacity-0">
        <div class="mb-2 flex items-center">
            <h2 class="text-lg font-bold">Filters</h2>
            <button type="button" x-on:click.prevent="isOpen = !isOpen" class="ml-auto btn text-lg p-3">
                <x-feather-icon name="times" class="h-current"></x-feather-icon>
            </button>
        </div>
        <div class="relative flex-1">
            <input id="filter-search" type="text" name="search" class="w-full form-input pl-8" value="{{ request('search', '') }}"
                placeholder="Search letters by subject or description...">
            <x-feather-icon name="search" class="w-5 text-gray-600 absolute left-0 ml-2 transform -translate-y-1/2" style="top: 50%;"></x-feather-icon>
        </div>
        @foreach ($filters as $filter)
        <div class="mb-2">
            <label class="w-full form-label mb-1">
                {{ $filter['label'] }}
            </label>
            @if($filter['type'] === 'select')
            <select name="filters[{{ $filter['name'] }}]"
                class="w-full form-select is-sm">
                <option value="" @if(request('filters.' . $filter['name'], '') == '') selected @endif>All</option>
                @foreach ($filter['options'] as $value => $option)
                    <option value="{{ $value }}"
                        @if(request('filters.' . $filter['name'], '') == $value) selected @endif>
                        {{ $option }}
                    </option>
                @endforeach
            </select>
            @else
            <input type="{{ $filter['type'] }}"
                name="filters[{{ $filter['name'] }}]"
                class="w-full form-input is-sm"
                value="{{ request('filters.' . $filter['name']) }}">
            @endif
        </div>
        @endforeach
        <div class="mt-4 mb-1">
            <button type="submit" class="btn btn-black is-sm">Apply</button>
        </div>
    </form>
</div>

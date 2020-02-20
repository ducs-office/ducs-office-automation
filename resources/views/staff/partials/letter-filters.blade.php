<toggle-visibility class="relative ml-auto">
    <template v-slot="{ isVisible, toggle }">
        <button class="mr-2 btn btn-black is-sm ml-auto" @click="toggle">
            <feather-icon name="filter" class="h-4" stroke-width="2"></feather-icon>
        </button>
        <transition enter-active-class="transition" leave-active-class="transition"
            enter-class="translate-x-100 opacity-0" leave-to-class="translate-x-100 opacity-0">
            <form method="GET" v-if="isVisible"
                class="absolute max-h-screen-1/2 right-0 top-100 border max-w-sm shadow-lg rounded overflow-y-auto bg-white p-4 z-10">
                <input type="text" name="search" class="w-full form-input is-sm w-full mb-4"
                    placeholder="Search keywords..">
                @foreach ($filters as $filter)
                <div class="mb-2">
                    <label class="w-full form-label mb-1">
                        {{ $filter['label'] }}
                    </label>
                    @if($filter['type'] === 'select')
                    <select name="filters[{{ $filter['field'] }}][{{ $filter['operator'] }}]"
                        class="w-full form-input is-sm">
                        <option value="">All</option>
                        @foreach ($filter['options'] as $value => $option)
                        <option value="{{ $value }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    @else
                    <input type="{{ $filter['type'] }}"
                        name="filters[{{ $filter['field'] }}][{{ $filter['operator'] }}]"
                        class="w-full form-input is-sm">
                    @endif
                </div>
                @endforeach
                <div class="mt-4 mb-1">
                    <button type="submit" class="btn btn-black is-sm">Apply</button>
                </div>
            </form>
        </transition>
    </template>
</toggle-visibility>

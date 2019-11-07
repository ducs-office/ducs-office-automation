<template>
    <div class="relative">
        <button class="mr-2 btn btn-black is-sm ml-auto" @click="toggleFilterView">
            <feather-icon name="filter" class="h-4" stroke-width="2"></feather-icon>
        </button>
        <transition enter-active-class="transition"
            leave-active-class="transition"
            enter-class="translate-x-100 opacity-0"
            leave-to-class="translate-x-100 opacity-0">
            <form method="GET" v-if="showing" class="absolute max-h-screen-1/2 right-0 top-100 border max-w-64 shadow-lg rounded overflow-y-auto bg-white p-4">
                <input type="text" name="search" class="w-full form-input is-sm w-full mb-4" placeholder="Search keywords..">

                <div v-for="(filter, index) in filters" :key="index" class="mb-2">
                    <label class="w-full form-label mb-1" v-text="filter.label"></label>
                    <select v-if="filter.type == 'select'" :name="`filters[${filter.field}][${filter.operator}]`" class="w-full form-input is-sm">
                        <option value="">All</option>
                        <option v-for="(value, option) in filter.options" :key="option" :value="option" v-text="value"></option>
                    </select>
                    <input v-else :type="filter.type" :name="`filters[${filter.field}][${filter.operator}]`" class="w-full w-full form-input is-sm">
                </div>
                <div class="mt-4 mb-1">
                    <button type="submit" class="btn btn-black is-sm">Apply</button>
                </div>
            </form>
        </transition>
    </div>
</template>
<script>
export default {
    props: {
        filters: {default: () => ([])}
    },
    data() {
        return {
            showing: false,
        }
    },
    methods: {
        toggleFilterView() {
            this.showing = !this.showing;
        }
    }
}
</script>
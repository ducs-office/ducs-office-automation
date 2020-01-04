<template>
    <div class="relative" v-click-outside="clear">
        <div class="flex items-center flex-wrap -mx-1 my-2" v-if="hasSelected">
            <div
                class="px-2 py-1 leading-none rounded text-sm bg-magenta-700 text-white m-1 flex items-center"
                v-for="(item, index) in selectedItems"
                :key="item[dataKey]"
            >
                <input type="hidden" :name="name" :value="item.id" />
                <slot name="selectedItems" :item="item" :removeItem="removeItem">
                    <span class="white-space-no-wrap mr-1" v-text="item.name"></span>
                    <button class="text-white-80 hover:text-red-600" type="button" @click="removeItem(index)">
                        <feather-icon name="x" stroke-width="3" class="h-4"></feather-icon>
                    </button>
                </slot>
            </div>
        </div>
        <input
            class="w-full form-input my-2"
            autocomplete="off"
            :placeholder="placeholder"
            v-model="query"
            @keydown.esc.prevent="clear"
            @keydown.tab="clear"
            @keydown.enter.prevent="toggleItem(highlightedItemIndex)"
            @keydown.up.prevent="moveUp"
            @keydown.down.prevent="moveDown"
        />
        <div v-if="options.length > 0" class="bg-white py-2 border rounded shadow-md">
            <ul ref="optionsList" class="max-h-40 overflow-y-auto">
                <li
                    v-for="(option, index) in options"
                    :key="index"
                    @click="toggleItem(index)"
                    @mouseover="highlightItem(index)"
                    class="px-3 py-1"
                    :class="{'bg-gray-200': isOptionSelected(index), 'bg-magenta-800 text-white': highlightedItemIndex == index}"
                >
                    <slot :option="option">
                        <p v-text="option.name"></p>
                    </slot>
                </li>
            </ul>
        </div>
        <span v-else-if="query.length > 0">No match found.</span>
    </div>
</template>
<script>
import ClickOutside from "../click-outside.js";

export default {
    directives: { ClickOutside },
    props: {
        name: { required: true },
        value: { default: null },
        data: { default: () => [] },
        multiple: { default: true },
        dataKey: { default: null },
        dataSearchIndices: { default: null },
        placeholder: { default: "" }
    },

    created() {
        if (!this.value) {
            this.selectedItems = this.multiple ? [] : null;
            return;
        }

        this.selectedItems = (Array.isArray(this.value) ? this.value : [this.value])
            .map(item => {
                if (typeof item === 'object' && item !== null) {
                    return item;
                }

                return this.data.find(dataItem => dataItem[this.dataKey] == item);
            }).filter(item => !!item)

        if (! this.multiple) {
            this.selectedItems = this.selectedItems.slice(0, 1);
        }
    },

    data() {
        return {
            query: "", // search query
            isOpen: false,
            selectedItems: [], // stores whole option
            highlightedItemIndex: 0 // just for visual highlight
        };
    },

    computed: {
        options() {
            return this.data.filter(
                item => ! this.selectedItems.find(sItem => this.itemEquals(item, sItem))
            ).filter(
                item => this.getSearchIndexValues(item).some(this.matchQuery)
            );
        },
        hasSelected() {
            return this.selectedItems.length;
        },
    },

    methods: {
        clear() {
            this.query = "";
        },

        optionSelectedIndex(index) {
            if (this.options.length <= index) {
                return -1;
            }

            return this.selectedItems.findIndex(
                item => this.itemEquals(item, this.options[index])
            );
        },

        isOptionSelected(index) {
            const foundIndex = this.optionSelectedIndex(index);

            return foundIndex == -1 ? false : true;
        },

        toggleItem(index) {
            const selectedIndex = this.optionSelectedIndex(index);

            if (selectedIndex != -1) {
                this.query = "";
                return this.removeItem(selectedIndex);
            }

            return this.selectItem(index);
        },
        removeItem(index) {
            this.selectedItems.splice(index, 1);
            this.$emit('input', this.selectedItems);
        },
        selectItem(index) {
            const selectedItem = this.options[index];
            if (this.multiple) {
                this.selectedItems.push(selectedItem);
            } else {
                this.selectedItems[0] = selectedItem;
            }
            this.$emit("input", this.selectedItems);
            this.query = "";
        },

        // visual navigations
        highlightItem(index) {
            this.highlightedItemIndex = index % this.options.length;

            if (this.highlightedItemIndex < 0) {
                this.highlightedItemIndex = this.options.length - 1;
            }

            this.$refs.optionsList.children[this.highlightedItemIndex].scrollIntoView({block: 'nearest'});
        },
        moveDown() {
            this.highlightItem(this.highlightedItemIndex + 1);
        },
        moveUp() {
            this.highlightItem(this.highlightedItemIndex - 1);
        },

        // utils
        getSearchIndexValues(item) {
            if (!this.dataSearchIndices) {
                return item;
            }

            return Array.isArray(this.dataSearchIndices)
                ? this.dataSearchIndices.map(indexKey => item[indexKey])
                : [item[this.dataSearchIndices]];
        },
        matchQuery(itemField) {
            if (Number.isInteger(itemField)) {
                return this.query == itemField;
            }

            return itemField.includes(this.query);
        },
        itemEquals(item, otherItem) {
            return item[this.dataKey] == otherItem[this.dataKey];
        }
    }
};
</script>


<style>
.flip-enter-active,
.flip-leave-active {
    transition: all 0.3s;
}
.flip-enter,
.flip-leave-to {
    opacity: 0;
    transform-origin: 50% 0;
    transform: translateY(50%);
}
</style>

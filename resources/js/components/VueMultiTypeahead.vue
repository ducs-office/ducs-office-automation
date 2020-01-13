<template>
    <div class="relative form-input" v-click-outside="cancel">
        <div v-if="hasSelected" class="flex flex-wrap -m-1 mb-1">
            <div class="p-1 inline-flex items-center leading-none rounded text-sm bg-magenta-700 text-white m-1"
                v-for="(item, index) in selectedItems"
                :key="item.id">
                <input type="hidden" :name="name" :value="item.id">
                <span class="white-space-no-wrap mr-1" v-text="item.name"></span>
                <button type="button" class="text-white-80" @click="removeItem(index)">
                  <feather-icon name="x" stroke-width="2.5" class="h-current"></feather-icon>
                </button>
            </div>
        </div>
        <input class="flex-1 bg-transparent"
          autocomplete="off"
          :placeholder="placeholder"
          :value="query"
          @input="onInput"
          @focus.prevent="onFocus"
          @keydown.esc.prevent="cancel"
          @keydown.tab="cancel"
          @keydown.enter.prevent="toggleItem(highlightedItemIndex)"
          @keydown.up.prevent="moveUp"
          @keydown.down.prevent="moveDown">
        <feather-icon v-if="loading" name="loader" class="absolute right-0 mr-1 absolute-y-center h-current"></feather-icon>
        <transition name="flip">
          <div class="absolute z-20 inset-x-0 top-100 mt-1 bg-white py-2 border rounded shadow-md" v-if="isOpen">
            <ul v-if="options.length > 0">
                <li v-for="(option, index) in options" :key="index"
                  @click="toggleItem(index)"
                  @mouseover="highlightItem(index)"
                  class="px-3 py-1"
                  :class="{'bg-gray-200': isOptionSelected(index), 'bg-magenta-800 text-white': highlightedItemIndex == index}">
                    <slot :option="option">
                      <p v-text="option.name"></p>
                    </slot>
                </li>
            </ul>
            <p v-else class="text-xs text-gray-600 px-3">
              <span v-if="query.length > 0">No result found.</span>
              <span v-else>Start typing to search ...</span>
            </p>
          </div>
        </transition>
    </div>
</template>
<script>
import ClickOutside from "../click-outside.js";
import axios from 'axios';
import debounce from 'lodash/debounce';

export default {
  props: {
    name: {required: true},
    value: {default: []},
    source: {required: true},
    findSource: {required: true},
    limit: {default: 10},
    placeholder: { default: "" }
  },
  directives: { ClickOutside },
  data() {
    return {
      query: "",
      isOpen: false,
      selectedItems: [],
      highlightedItemIndex: 0,
      loading: false,
      options: []
    };
  },
  methods: {
    onInput(event) {
      this.query = event.target.value;

      if(this.query != '' && this.source != null) {
        this.searchOptions();
      }
    },
    onFocus() {
      this.open();
      if(this.hasSelected) {
        this.query = '';
        this.updateOptions([]);
      }
    },
    open() {
      this.isOpen = true;
    },
    close () {
      this.isOpen = false;
    },

    cancel() {
      this.query = '';
      this.close();
    },

    searchOptions: debounce(function() {
      this.loading = true;
      axios.get(this.source, {
        params: { q: this.query, limit: this.limit }
      }).then(({data}) => {
        this.updateOptions(data);
        this.loading = false;
      });
    }, 400),

    optionSelectedIndex(index) {
        if(this.options.length <= index) {
            return false;
        }

        return this.selectedItems.findIndex(
            item => item.id == this.options[index].id
        );
    },

    isOptionSelected(index) {
        const foundIndex = this.optionSelectedIndex(index);

        return foundIndex == -1 ? false : true;
    },

    toggleItem(index) {
        const selectedIndex = this.optionSelectedIndex(index);
        if (selectedIndex != -1) {
            this.query = '';
            return this.removeItem(selectedIndex);
        }

        return this.selectItem(index);
    },

    removeItem(index) {
        this.selectedItems.splice(index, 1);
    },

    selectItem(index) {
        const selectedItem = this.options[index];
        this.selectedItems.push(selectedItem)
        this.isOpen = false;
        this.$emit("input", selectedItem);
        this.query = '';
    },

    highlightItem(index) {
      this.highlightedItemIndex = index % this.options.length;

      if (this.highlightedItemIndex < 0) {
        this.highlightItem(this.options.length - 1);
      }
    },

    updateOptions(data) {
        if(!this.isOpen) {
            this.open();
        }

        if(!this.hasSelected) {
            this.options = data;
            return true;
        }

        const selectedIds = this.selectedItems.map(item => item.id)

        this.options = [
            ...data.filter(item => ! selectedIds.includes(item.id)),
            ...this.selectedItems,
        ];

        this.highlightedItemIndex = 0;
    },
    moveDown() {
      this.highlightItem(this.highlightedItemIndex + 1);
    },
    moveUp() {
      this.highlightItem(this.highlightedItemIndex - 1);
    }
  },
  computed: {
    hasSelected() {
      return this.selectedItems.length;
    }
  },

  async created() {
    if(this.value.length) {
        this.loading = true;

        const requests = this.value.filter(id => /[0-9]+/.test(id))
            .map(id => axios.get(this.findSource.replace('{value}', id)));

        const responses = await Promise.all(requests);
        
        this.loading = false;
        this.selectedItems = responses.map(res => res.data)
        this.updateOptions(this.selectedItems);
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

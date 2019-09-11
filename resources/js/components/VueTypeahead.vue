<template>
    <div class="relative" v-click-outside="cancel">
        <input type="hidden" :name="name" :value="hasSelected ? selectedItem.id : ''">
        <input class="form-input pr-4" 
          autocomplete="off"
          :placeholder="placeholder"
          :value="query"
          @input="onInput"
          @focus.prevent="onFocus" 
          @keydown.esc.prevent="cancel"
          @keydown.tab="cancel"
          @keydown.enter.prevent="selectItem(highlightedItemIndex)" 
          @keydown.up.prevent="moveUp"
          @keydown.down.prevent="moveDown">
        <feather-icon v-if="loading" name="loader" class="absolute right-0 mr-1 absolute-y-center h-current"></feather-icon>
        <transition name="flip">
          <div class="absolute inset-x-0 top-100 mt-1 bg-white py-2 border rounded shadow-md" v-if="isOpen">
            <ul v-if="options.length > 0">
                <li v-for="(option, index) in options" :key="index" 
                  @click="selectItem(index)"
                  @mouseover="highlightItem(index)"
                  class="px-3 py-1"
                  :class="{'bg-gray-200': selectedItemIndex == index, 'bg-gray-900 text-white': highlightedItemIndex == index}">
                    <slot :option="option">
                      <p v-text="option.name"></p>
                    </slot>
                </li>
            </ul>
            <p v-else class="text-xs text-gray-600 px-3"> 
              <span v-if="query.length > 0">No users found.</span>
              <span v-else>Start typing to search user...</span>
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
    value: {default: null},
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
      selectedItemIndex: -1,
      highlightedItemIndex: 0,
      loading: false,
      options: [
      ]
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
      this.query = this.hasSelected ? this.selectedItem.name : '';
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
    selectItem(index) {
      this.selectedItemIndex = index;
      this.isOpen = false;
      this.$emit("input", this.selectedItem);
      this.query = this.selectedItem.name;
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

      this.options = [this.selectedItem, ...data];
      this.selectedItemIndex = 0;
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
      return this.selectedItemIndex != -1;
    },
    selectedItem() {
      return this.options[this.selectedItemIndex];
    }
  },
  created() {
    if(this.value != null && (/[0-9]+/).test(this.value)) {
      this.loading = true;
      axios.get(this.findSource.replace('{value}', this.value))
        .then(({data}) => {
          this.updateOptions([data]);
          this.selectItem(0);
          this.loading = false;
        });
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

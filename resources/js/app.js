require('./bootstrap');

import Vue from 'vue';
import FeatherIcon from './components/FeatherIcon.vue';
import VueTypeahead from './components/VueTypeahead.vue';
import FilterLetters from './components/FiltersComponent.vue';

Vue.component('feather-icon', FeatherIcon);
Vue.component('vue-typeahead', VueTypeahead);
Vue.component('filter-letters', FilterLetters);

const app = new Vue({
    el: '#app'
});
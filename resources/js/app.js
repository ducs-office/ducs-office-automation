require('./bootstrap');

import Vue from 'vue';
import FeatherIcon from './components/FeatherIcon.vue';
import VueTypeahead from './components/VueTypeahead.vue';

Vue.component('feather-icon', FeatherIcon);
Vue.component('vue-typeahead', VueTypeahead);

const app = new Vue({
    el: '#app'
});
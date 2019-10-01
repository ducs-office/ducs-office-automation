require('./bootstrap');

import Vue from 'vue';
import VueJsModal from 'vue-js-modal';
import FeatherIcon from './components/FeatherIcon.vue';
import VueTypeahead from './components/VueTypeahead.vue';

Vue.use(VueJsModal);

Vue.component('feather-icon', FeatherIcon);
Vue.component('vue-typeahead', VueTypeahead);

const app = new Vue({
    el: '#app'
});

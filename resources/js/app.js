require('./bootstrap');

import Vue from 'vue';
import FeatherIcon from './components/FeatherIcon.vue';

Vue.component('feather-icon', FeatherIcon);

const app = new Vue({
    el: '#app'
});
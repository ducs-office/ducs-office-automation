require('./bootstrap');

import Vue from 'vue';
import VueJsModal from 'vue-js-modal';
import FeatherIcon from './components/FeatherIcon.vue';
import VueTypeahead from './components/VueTypeahead.vue';
import CourseUpdateModal from './components/CourseUpdateModal.vue';

Vue.use(VueJsModal);

Vue.component('feather-icon', FeatherIcon);
Vue.component('vue-typeahead', VueTypeahead);
Vue.component('course-update-modal', CourseUpdateModal);

const app = new Vue({
    el: '#app'
});

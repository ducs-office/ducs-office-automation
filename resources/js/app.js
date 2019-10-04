require('./bootstrap');

import Vue from 'vue';
import VueJsModal from 'vue-js-modal';

import Flash from './components/Flash.vue';
import FeatherIcon from './components/FeatherIcon.vue';
import VueTypeahead from './components/VueTypeahead.vue';
import CourseUpdateModal from "./components/CourseUpdateModal.vue";
import PaperUpdateModal from './components/PaperUpdateModal.vue';
import LettersSearchFilters from './components/LettersSearchFilters.vue';
import CollegeUpdateModal from './components/CollegeUpdateModal.vue';
import SidebarNav from './components/SidebarNav.vue';
import SidebarNavButton from './components/SidebarNavButton.vue';

Vue.use(VueJsModal);

Vue.component('v-flash', Flash);
Vue.component('feather-icon', FeatherIcon);
Vue.component('vue-typeahead', VueTypeahead);
Vue.component("course-update-modal", CourseUpdateModal);
Vue.component("paper-update-modal", PaperUpdateModal);
Vue.component('letter-search-filters', LettersSearchFilters);
Vue.component("college-update-modal", CollegeUpdateModal);
Vue.component("sidebar-nav", SidebarNav);
Vue.component("sidebar-nav-button", SidebarNavButton);

window.Events = new Vue();

const app = new Vue({
    el: '#app'
});

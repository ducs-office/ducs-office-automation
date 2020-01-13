require('./bootstrap');

import Vue from 'vue';
import VueJsModal from 'vue-js-modal';

import Flash from './components/Flash.vue';
import FeatherIcon from './components/FeatherIcon.vue';
import VueTypeahead from './components/VueTypeahead.vue';
import VueMultiTypeahead from './components/VueMultiTypeahead.vue';
import TabbedPane from './components/TabbedPane.vue';
import FileInput from './components/FileInput.vue';
import DynamicModal from './components/DynamicModal.vue';
import ToggleVisibility from './components/ToggleVisibility';
import CourseSections from'./components/CourseSections.vue';

import ClickOutside from './click-outside.js';

Vue.use(VueJsModal);

window.Events = new Vue();

Vue.component('v-flash', Flash);
Vue.component('v-modal', DynamicModal);
Vue.component('feather-icon', FeatherIcon);
Vue.component('vue-typeahead', VueTypeahead);
Vue.component('v-multi-typeahead', VueMultiTypeahead);
Vue.component("v-tabbed-pane", TabbedPane);
Vue.component("v-file-input", FileInput);

Vue.component('toggle-visibility', ToggleVisibility);
Vue.component('course-sections', CourseSections);

Vue.mixin({
    computed: {
        $window: () => window
    },
    directives: { ClickOutside },
    methods: {
        route: route,
    }
});

window.Events = new Vue();

const app = new Vue({
    el: '#app'
});

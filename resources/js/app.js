require('./bootstrap');

import Vue from 'vue';
import VueJsModal from 'vue-js-modal';

import Flash from './components/Flash.vue';
import FeatherIcon from './components/FeatherIcon.vue';
import VueTypeahead from './components/VueTypeahead.vue';
import VueMultiTypeahead from "./components/VueMultiTypeahead.vue";
import VueSelect from './components/VueSelect.vue';
import TabbedPane from './components/TabbedPane.vue';
import FileInput from "./components/FileInput.vue";
import ImageUploadInput from './components/ImageUploadInput.vue';
import DynamicModal from './components/DynamicModal.vue';
import ToggleVisibility from './components/ToggleVisibility';
import ProgrammeForm from'./components/ProgrammeForm.js';
import SemesterWiseCourseInput from './components/SemesterWiseCoursesInput.vue';
import AddRemoveElements from './components/AddRemoveElements.js';
import CourseProgrammeRevisionSelector from './components/CourseProgrammeRevisionSelector.vue';
import ScholarEducation from './components/ScholarEducation.vue';

import ClickOutside from './click-outside.js';

Vue.use(VueJsModal);

window.Events = new Vue();

Vue.component('v-flash', Flash);
Vue.component('v-modal', DynamicModal);
Vue.component('feather-icon', FeatherIcon);
Vue.component('vue-typeahead', VueTypeahead);
Vue.component("v-multi-typeahead", VueMultiTypeahead);
Vue.component('v-select', VueSelect);
Vue.component("v-tabbed-pane", TabbedPane);
Vue.component("v-file-input", FileInput);
Vue.component("image-upload-input", ImageUploadInput);

Vue.component('toggle-visibility', ToggleVisibility);
Vue.component('programme-form', ProgrammeForm);
Vue.component("semester-wise-courses-input", SemesterWiseCourseInput);
Vue.component('add-remove-elements', AddRemoveElements);
Vue.component("course-programme-revision-selector", CourseProgrammeRevisionSelector);
Vue.component('scholar-education', ScholarEducation);


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

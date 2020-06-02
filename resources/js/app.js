require('./bootstrap');
import 'alpinejs';
import feather from 'feather-icons';
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
import SelectWithOther from './components/SelectWithOther.vue';
import AdvisoryCommitteeMemberElement from './components/AdvisoryCommitteeMemberElement.vue';


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
Vue.component('select-with-other', SelectWithOther);
Vue.component('advisory-committee-member', AdvisoryCommitteeMemberElement);


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

if(document.getElementById('app')) {
    const app = new Vue({
        el: '#app'
    });
}

window.$modals = {
    open: (name, livewire = null) => {
        window.dispatchEvent(
            new CustomEvent('openmodal', { detail: { name, livewire } })
        );
        if(livewire) {
            window.livewire.emitTo(
                livewire.component || name,
                livewire.event || 'show',
                livewire.payload || {}
            );
        }
    },
    close: (name) => window.dispatchEvent(
        new CustomEvent('closemodal', { detail: { name } })
    ),
};

window.modal = (name, isOpen = false) => ({
    name,
    isOpen,
    close() {
        window.$modals.close(this.name);
    },
    handleOpenEvent(event) {
        // close the modal if any other modal was fired open
        this.isOpen = event.detail.name == name;
    },
    handleCloseEvent(event) {
        if (event.detail.name == this.name) {
            this.isOpen = false;
        }
    }
});

window.dropdown = (dropdownName = '$dropdown') => ({
    [dropdownName]: {
        isOpen: false,
        open() { this.isOpen = true },
        close() { this.isOpen = false },
        toggle() { this.isOpen = ! this.isOpen }
    }
});

window.tabbedPane = (initial = '') => ({
    $tabs: {
        current: initial,
        isActive(name) {
            return this.current === name;
        },
        switchTo(name) {
            this.current = name;
        }
    }
});

window.featherIcon = function(name = 'x') {
    return feather.icons.hasOwnProperty(name)
        ? feather.icons[name].contents
        : feather.icons["x"].contents;
}

window.addRemoveElement = (count = 1, max = Infinity, newItem = () => '') => ({
    items: new Array(count).fill(0).map(() => newItem()),
    add() {
        if (this.items.length == max) {
            return;
        }
        this.items.push(newItem());
    },
    remove(index) {
        if (index < 0 || index >= this.items.length) {
            return;
        }

        if (this.items.length == 0) {
            return;
        }

        this.items.splice(index, 1);
    },
    initialise(items = []) {
        this.items.push(...items);
    }
});

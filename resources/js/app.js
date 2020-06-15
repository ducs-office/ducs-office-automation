import 'alpinejs';
import feather from 'feather-icons/dist/icons.json';
import CustomSelect from './custom-select';

window.CustomSelect = CustomSelect;

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
    return feather.hasOwnProperty(name)
        ? feather[name] : feather["x"];
}
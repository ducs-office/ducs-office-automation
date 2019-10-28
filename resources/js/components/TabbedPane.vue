<template>
    <div>
        <slot name="tabs" :isActive="isActive" :select="select">
            Tabs will come here
        </slot>
        <slot :isActive="isActive">
            Tabs Content will come here
        </slot>
    </div>
</template>
<script>

export default {
    name: 'TabbedPane',
    props: {
        defaultTab: {default: null}
    },
    data() {
        return {
            activeTab: this.defaultTab,
        }
    },
    methods: {
        select(name) {
            this.activeTab = name
        },
        isActive(tab) {
            return this.activeTab === tab;
        }
    }
}
</script>
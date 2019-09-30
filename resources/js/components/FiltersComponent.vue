<template>
    <div class="relative pr-12 flex justify-between items-end py-2 px-6 mb-2 my-2">
        <button class="absolute top-0 right-0 mr-2 btn btn-black is-sm ml-auto" @click="toggle">
            <feather-icon name="filter" class="h-4" stroke-width="2"></feather-icon>
        </button>
        <form v-show="open" method="GET" class="flex flex-wrap items-end -mx-2">
            <input type="text" name="search" class="form-input w-full mx-2 mb-2" placeholder="Search for letters...">
            <input type="text" name="after" 
                placeholder="After date"
                class="form-input is-sm mx-2 my-2" 
                onfocus="this.type='date'"
                onblur="this.type='text'">

            <input type="text" name="before" 
                placeholder="Before date"
                class="form-input is-sm mx-2 my-2" 
                onfocus="this.type='date'"
                onblur="this.type='text'">
                
            <select name="filters[recipient][equals]" id="recipient" class="form-input is-sm mx-2 my-2">
                <option value="" selected>Recipient</option>
                <option v-for="recipient in recipients" :key="recipient" :value="recipient" v-text="recipient"></option>
            </select>

            <select name="filters[type][equals]" id="type" class="form-input is-sm mx-2 my-2">
                <option value="" selected>Type</option>
                <option v-for="type in types" :key="type" :value="type" v-text="type"></option>
            </select>

            <select name="filters[sender_id][equals]" id="type" class="form-input is-sm mx-2 my-2">
                <option value="" selected>Sender</option>
                <option v-for="sender in senders" :value="sender.id" :key="sender.id" v-text="sender.name"></option>
            </select>

            <select name="filters[creator_id][equals]" id="type" class="form-input is-sm m-2">
                <option value="" selected>creator</option>
                <option  v-for="creator in creators" :key="creator.id" :value="creator.id" v-text="creator.name"></option>
            </select>
                
            <button type="submit" class="btn btn-black is-sm m-2">Apply Filter</button>
        </form>
    </div>
</template>
<script>
export default {
    props: [
        'types', 'senders', 'creators', 'recipients'
    ],
    data() {
        return {
            open: false
        }
    },
    methods: {
        toggle() {
            this.open = ! this.open;
        }
    }
}
</script>
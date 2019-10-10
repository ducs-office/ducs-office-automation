
<template>
    <modal name = "remark-modal" height="auto" @before-open="beforeOpen">
        <div class = "p-6">
            <div class = "flex justify-between items-baseline">
                <h2 class = "text-lg font-bold mb-8">Remarks</h2>
                <button v-show="!showAddForm" class="btn is-sm btn-magenta mr-3" v-on:click="openAddForm()">New</button>
            </div>
            <div v-show="showAddForm" class = "flex items-baseline mb-2">
                <form :action="`/outgoing-letters/${outgoing_letter.id}/remarks`" method="POST" class = "flex  items-baseline w-full">
                    <slot></slot>
                    <input type="text" id="description" placeholder="Description" class="w-full form-input" name="description">
                    <button class="mr-2 ml-2 p-1 hover:bg-gray-200 text-green-700 rounded" v-on:click="closeAddForm()" type="submit">
                        <feather-icon name="plus-circle" stroke-width="3" class="h-current">Add</feather-icon>
                    </button>
                </form>
                <button class="mr-2 p-1 hover:bg-gray-200 text-red-700 rounded " v-on:click="closeAddForm()">
                    <feather-icon name="x-circle" stroke-width="3" class="h-current">Cancel</feather-icon>
                </button>
            </div>
            <div class="px-6 py-2 hover:bg-gray-100 border-b justify-between overflow-y-auto">
                <div class="flex  mb-2" v-for="(remark,id) in remarks" :key="id" :value="id" >
                    <h4 class="font-bold text-sm text-gray-600 w-24">{{remark.updated_at}}</h4>
                    <div class="flex" v-show="!remark.editRemark">
                        <h4 class="font-bold text-lg mr-2">{{ remark.description }}</h4>
                        <div class = "flex ml-auto items-baseline">
                            <button v-on:click="openEditForm(remark)" class="p-1 text-gray-500 hover:bg-gray-200 hover:text-blue-600 rounded" title="Edit">
                                <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                            </button>
                            <form :action="`/remarks/${remark.id}/`" method="POST">
                                <slot></slot>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                                    <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div  v-show="remark.editRemark" class = "flex items-baseline">
                        <form :action="`/remarks/${remark.id}/`" method="POST" class="flex items-baseline">
                            <slot></slot>
                            <input type="hidden" name="_method" value="PATCH">
                            <input id="description"  type="text" name="description" class="w-full form-input" v-model="remark.description">  
                            <button class="btn is-sm btn-magenta mr-2 ml-4" type="submit" v-on:click="closeEditForm(remark)">Update</button>
                        </form>
                        <button class="mr-2 p-1 hover:bg-gray-200 rounded is-sm btn btn-magenta" v-on:click="closeEditForm(remark)">
                                Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </modal>
</template>
<script>
import Vue from 'vue';
export default {
    data() {
        return {
            outgoing_letter: {
                id: ''
            },
            remarks: [ 
            ],
            showAddForm: false
        }
    },
    methods: {
        beforeOpen(event) {
            this.outgoing_letter = event.params.letter;
            this.remarks = event.params.remarks;
            this.showAddForm = false;
            this.remarks.forEach(function(remark) {
               Vue.set(remark , 'editRemark', false );
            });
        },
        openAddForm() {
            console.log("here");
            console.log(this.showAddForm);
            this.showAddForm = true;
        },
        closeAddForm() {
            this.showAddForm = false;
        },
        openEditForm(remark) {
            remark.editRemark = true;
        },
        closeEditForm(remark) {
            remark.editRemark = false;
        }
    }
}
</script>
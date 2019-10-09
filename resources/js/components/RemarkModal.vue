
<template>
    <modal name = "remark-modal" height="auto" @before-open="beforeOpen">
        <div class = "p-6">
            <h2 class = "text-lg font-bold mb-8">Remarks</h2>
            <div class="px-6 py-2 hover:bg-gray-100 border-b justify-between">
                <div class="flex items-baseline mb-2 justify-between" v-for="(remark,id) in remarks" :key="id" :value="id" >
                    <h4 class="font-bold text-sm text-gray-600 w-24">{{remark.updated_at}}</h4>
                    <div class="flex justify-between" v-show="!remark.editRemark">
                        <h4 v-on:click="openEditForm(remark)" class="font-bold text-lg  mr-2">{{ remark.description }}</h4>
                        <form :action="`/remarks/${remark.id}/`" method="POST">
                            <slot></slot>
                            <button type="submit" class="mr-2 p-1 hover:bg-gray-200 text-red-700 rounded">
                                 <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                            </button>
                        </form>
                    </div>
                    <div  v-show="remark.editRemark">
                        <form class="flex" :action="`/remarks/${remark.id}/`" method="POST">
                            <input id="description" v-on:blur="closeEditForm(remark)" type="text" name="description" class="w-full form-input" v-model="remark.description">  
                            <input type="hidden" name="_token" id="csrf-token" value="csrf" />
                            <input type="hidden" name="_method" value="PUT">
                            <button class="btn btn-magenta mr-2" type="submit">Update</button>
                        </form>
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
            letter: {
                id: ''
            },
            remarks: [ 
            ],
        }
    },
    methods: {
        beforeOpen(event) {
            this.letter = event.params.letter;
            this.remarks = event.params.remarks;
            this.remarks.forEach(function(remark) {
               Vue.set(remark , 'editRemark', false );
            })
        },
        openEditForm(remark) {
            remark.editRemark = true;
        },
        closeEditForm(remark) {
            console.log("here");
            remark.editRemark = false;
            console.log(remark.editRemark);
        }
    }
}
</script>
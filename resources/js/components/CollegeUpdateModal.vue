<template>
    <modal name="college-update-modal" height="auto" @before-open="beforeOpen">
        <div class = "p-6">
            <h2 class="text-lg font-bold mb-8">Update College</h2>
            <form :action="route('colleges.update', college)" method="POST" class="flex items-end">
                <slot></slot>
                <div class="flex-1 mr-2">
                    <label for="college_code" class="w-full form-label">College Code</label>
                    <input id="college_code" type="text" name="code" class="w-full form-input" v-model="college.code">
                </div>
                <div class="flex-1 mr-5">
                    <label for="college_name" class="w-full form-label">College</label>
                    <input id="college_name" type="text" name="name" class="w-full form-input" v-model="college.name">
                </div>
                <div>
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </modal>
</template>
<script>
    export default {
        data() {
            return {
                college: {
                    id: '',
                    code: '',
                    name: '',
                }
            }
        },
        methods: {
            beforeOpen(event) {
                if(!event.params.college || !event.params.college.id){
                    return false;
                }

                this.college = event.params.college;
            }
        }
    }
</script>

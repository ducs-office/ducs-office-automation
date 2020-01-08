<template>
    <modal name="college-update-modal" height="auto" @before-open="beforeOpen">
        <div class = "p-6">
            <h2 class="text-lg font-bold mb-8">Update College</h2>
            <form :action="route('colleges.update', college)" method="POST" class="px-6">
                <slot></slot>
                <div class="items-baseline">
                    <div class="mb-2">
                        <label for="college_code" class="w-full form-label">College Code<span class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_code" type="text" name="code" class="w-full form-input" v-model="college.code">
                    </div>
                    <div class="mb-5">
                        <label for="college_name" class="w-full form-label">College<span class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_name" type="text" name="name" class="w-full form-input" v-model="college.name">
                    </div>
                    <div class="mb-2">
                        <label for="programme" class="w-full form-label">Programmes<span class="h-current text-red-500 text-lg">*</span></label>
                        <select name="programmes[]" id="programme" v-model="college_programmes" class="mt-2 w-full form-input" multiple>
                            <option v-for="programme in programmes"
                                :key="programme.id" :value="programme.id"
                                v-text="programme.name"
                            >
                            </option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-magenta">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </modal>
</template>
<script>
    export default {
        props: ['programmes'],
        data() {
            return {
                college: {
                    id: '',
                    code: '',
                    name: '',
                },
                college_programmes: []
            }
        },
        methods: {
            beforeOpen(event) {
                if(!event.params.college || !event.params.college.id || !event.params.college_programmes){
                    return false;
                }

                this.college = event.params.college;
                this.college_programmes = event.params.college_programmes;
            }
        }
    }
</script>

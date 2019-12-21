<template>
    <modal name="role-update-modal" height="auto" @before-open="beforeOpen">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Role</h2>
            <form :action="route('roles.update', role)" method="POST">
                <slot></slot>
                <div class="mb-2">
                    <label for="name" class="w-full form-label mb-1">Role Name</label>
                    <input id="name" type="text" name="name" class="w-full form-input" v-model="role.name" placeholder="Enter a name for the role..." required>
                </div>
                <div class="mb-5">
                    <label for="permissions" class="w-full form-label mb-1">Assign Permissions</label>
                    <select id="permissions" name="permissions[]" class="w-full form-input" v-model="role_permissions" multiple>
                        <option v-for="permission in permissions"
                            :key="permission.id" :value="permission.id"
                            v-text="permission.name"></option>
                    </select>
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
    props: ['permissions'],
    data() {
        return {
            role: {
                id: '',
                name: '',
            },
            role_permissions: []
        }
    },
    methods: {
        beforeOpen(event) {
            if(!event.params.role || !event.params.role_permissions) {
                return false;
            }

            this.role = event.params.role;
            this.role_permissions = event.params.role_permissions;
        },
    }
}
</script>

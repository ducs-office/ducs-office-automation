<template>
    <modal name="role-update-modal" height="auto" @before-open="beforeOpen">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Role</h2>
            <form :action="route('roles.update', role)" method="POST">
                <slot></slot>
                <div class="mb-2">
                    <label for="name" class="w-full form-label mb-1">Role Name<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="name" type="text" name="name" class="w-full form-input" v-model="role.name" placeholder="Enter a name for the role..." required>
                </div>
                <div class="mb-5">
                    <label for="permissions" class="w-full form-label mb-1">Assign Permissions<span class="h-current text-red-500 text-lg">*</span></label>
                    <table>
                            <tr class="py-1" v-for="(gPermissions, group) in permissions" :key="group">
                                <th class="px-2" v-text="`${group} : `"></th>
                                <td class="px-2">
                                        <label v-for="permission in gPermissions"
                                            :key="permission.id"
                                            :for="`permission-${ permission.id }`"
                                            class="px-2 py-1 border rounded inline-flex items-center mr-3">
                                            <input :id="`permission-${ permission.id }`"
                                            type="checkbox"
                                            name="permissions[]"
                                            class="mr-1"
                                            :value="permission.id"
                                            :checked="role_permissions.includes(permission.id)">
                                            <span v-text="permission.name.split(':')[1]"></span>
                                        </label>
                                </td>
                            </tr>
                    </table>
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

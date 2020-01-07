<template>
    <modal name="user-update-modal" height="auto" @before-open="beforeOpen">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update User</h2>
            <form :action="route('users.update', user)" method="POST">
                <slot></slot>
                <div class="mb-2">
                    <label for="name" class="w-full form-label mb-1">Full Name<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="name" type="text" name="name" class="w-full form-input" v-model="user.name">
                </div>
                <div class="mb-2">
                    <label for="email" class="w-full form-label mb-1">Email<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="email" type="email" name="email" class="w-full form-input" v-model="user.email">
                </div>
                <div class="mb-5">
                    <label for="roles" class="w-full form-label mb-1">Roles<span class="h-current text-red-500 text-lg">*</span></label>
                    <select id="roles" name="roles[]" class="w-full form-input" v-model="user_roles" multiple>
                        <option v-for="role in roles"
                            :key="role.id" :value="role.id"
                            v-text="role.name"></option>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="category" class="w-full form-label mb-1">Category <span class="h-current text-red-500 text-lg">*</span></label>
                    <select name="category" id="category" class="w-full form-input" v-model="user.category">
                        <option v-for="category in categories"
                            :key="category" :value="category"
                            v-text="category">
                        </option>
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
    props: ['roles', 'categories'],
    data() {
        return {
            user: {
                id: '',
                name: '',
                email: '',
                category: '',
            },
            user_roles: []
        }
    },
    methods: {
        beforeOpen(event) {
            if(!event.params.user || !event.params.user_roles) {
                return false;
            }

            this.user = event.params.user;
            this.user_roles = event.params.user_roles;
        },
        isRolePresent(checkRole) {
            return !!this.user_roles.find(role => role.id == checkRole.id);
        }
    }
}
</script>

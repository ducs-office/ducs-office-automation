<v-modal name="{{ $modalName }}" height="auto">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-8">Create Scholar</h2>
        <form action="{{ route('staff.scholars.store') }}" method="POST" class="px-6">
            @csrf_token
            <div class="mb-2">
                <label for="first_name" class="w-full form-label">First Name<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="first_name" type="text" name="first_name" class="w-full form-input"
                    placholder="Enter first name here..." required>
            </div>
            <div class="mb-2">
                <label for="last_name" class="w-full form-label">Last Name<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="last_name" type="text" name="last_name" class="w-full form-input"
                    placholder="Enter last name here..." required>
            </div>
            <div class="mb-2">
                <label for="email" class="w-full form-label">Email<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="email" type="email" name="email" class="w-full form-input"
                    placholder="Enter user's email here..." required>
            </div>
            <div class="mt-5">
                <button class="btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
</v-modal>
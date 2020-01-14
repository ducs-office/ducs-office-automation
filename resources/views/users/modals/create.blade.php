<v-modal name="{{ $modalName }}" height="auto">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-8">Create Users</h2>
        <form action="{{ route('users.store') }}" method="POST" class="px-6">
            @csrf_token
            <div class="mb-2">
                <label for="name" class="w-full form-label">Full Name<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="name" type="text" name="name" class="w-full form-input"
                    placholder="Enter user's full name here..." required>
            </div>
            <div class="mb-2">
                <label for="email" class="w-full form-label">Email<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="email" type="email" name="email" class="w-full form-input"
                    placholder="Enter user's email here..." required>
            </div>
            <div class="mb-2">
                <label for="role" class="w-full form-label">Role<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <select id="role" name="roles[]" class="w-full form-input" multiple>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ ucwords(str_replace('_', ' ', $role->name)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label for="category" class="w-full form-label">Category<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <select name="category" id="category" class="w-full form-input" required>
                    <option value="" selected disabled>Select a Category:</option>
                    @foreach ($categories as $key => $category)
                        <option value="{{ $key }}"> {{ $category }} </option>
                    @endforeach
                </select>
            </div>
            <div class="mt-5">
                <button class="btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
</v-modal>

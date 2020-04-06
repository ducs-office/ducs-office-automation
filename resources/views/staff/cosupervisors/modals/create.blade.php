<v-modal name="{{ $modalName }}" height="auto">
    <form action="{{ route('staff.cosupervisors.store') }}" method="POST" class="p-6">
        <h2 class="mb-8 font-bold text-lg">Create New Co-supervisor</h2>
        @csrf_token
        <div class="mb-2">
            <label for="name" class="w-full form-label mb-1">Name
                <span class="text-red-600">*</span></label>
            <input name="name" type="text" class="w-full form-input" placeholder="Enter Name">
        </div>
        <div class="mb-2">
            <label for="email" class="w-full form-label mb-1">Email
                <span class="text-red-600">*</span></label>
            <input name="email" type="text" class="w-full form-input" placeholder="Enter Email">
        </div>
        <div class="mb-2">
            <label for="designation" class="w-full form-label mb-1">Designation
            <input name="designation" type="text" class="w-full form-input" placeholder="Enter Designation">
        </div>
        <div class="mb-2">
            <label for="affiliation" class="w-full form-label mb-1">Affiliation
            <input name="affiliation" type="text" class="w-full form-input" placeholder="Enter Affiliation">
        </div>
        <div class="mt-5">
            <button type="submit" class="btn btn-magenta">Create</button>
        </div>
    </form>
</v-modal>

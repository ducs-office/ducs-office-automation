<form action="{{ route('staff.cosupervisors.store') }}" method="POST" class="flex items-end space-x-2">
    @csrf_token
    <div class="flex-1">
        <label for="name" class="w-full form-label mb-1">Teacher
            <span class="text-red-600">*</span></label>
        <select name="user_id" type="text" class="w-full form-select">
            <option value="" disabled selected>-- Select Teacher --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="">
        <button type="submit" class="btn btn-magenta">Create</button>
    </div>
</form>

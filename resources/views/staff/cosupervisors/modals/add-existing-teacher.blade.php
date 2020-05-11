<v-modal name="{{ $modalName }}" height="auto">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-8">Add From Existing Teacher</h2>
        <div class="mb-2 border overflow-hidden rounded-lg">
        @foreach ($teachers as $teacher)
            <div class="border-b last:border-b-0">
                <form action="{{ route('staff.cosupervisors.store') }}" method="POST" class="px-6">
                    @csrf_token
                    <input type="hidden" name="user_id" value="{{ $teacher->id }}">
                    <div class="flex justify-between m-2">
                        <p class="font-bold pt-1">{{ ucwords($teacher->name) }}
                            <span class="font-bold text-gray-700 ml-2">{{ $teacher->email }}</span>
                        </p>

                        <button type="submit" class="btn btn-magenta is-sm">Add</button>

                    </div>
                </form>
            </div>
        @endforeach
        </div>
    </div>
</v-modal>

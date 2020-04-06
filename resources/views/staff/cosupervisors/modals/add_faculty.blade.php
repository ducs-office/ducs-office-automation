<v-modal name="{{ $modalName }}" height="auto">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-8">Add From Faculty</h2>
        <div class="mb-2 border overflow-hidden rounded-lg">
        @foreach ($faculties as $faculty)
            <div class="border-b last:border-b-0">
                <form action="{{ route('staff.cosupervisors.faculties.store', $faculty->id) }}" method="POST" class="px-6">
                    @csrf_token
                    <div class="flex justify-between m-2">
                        <p class="font-bold pt-1">{{ ucwords($faculty->name) }} 
                            <span class="font-bold text-gray-700 ml-2">{{ $faculty->email }}</span>
                        </p>
                       
                        <button type="submit" class="btn btn-magenta is-sm">Add</button>
                        
                    </div>
                </form>
            </div>
        @endforeach
        </div>
    </div>
</v-modal>

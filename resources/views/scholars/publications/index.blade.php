<div class="mt-6 page-card">
    <div class="flex items-baseline px-6 mb-4">
        <div class="w-60 pr-4 relative z-10 -ml-8 my-2">
            <h3 class="relative z-20 pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Publications
            </h3>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>
        <a class="ml-auto btn btn-magenta is-sm shadow-inset" 
            href="{{ route('scholars.profile.publication.store')}}">
            New    
        </a>
    </div>
    <div class="mt-4 px-6">
        <ul class="border rounded-lg overflow-hidden mb-4">
            @foreach ($publications as $publication)
                <li class="border-b last:border-b-0 py-3">
                    <div class="flex">
                        @include('scholars.partials.academic_details_index', [
                            'paper' => $publication,
                            'index' => $loop->iteration,
                        ])
                        <div class="ml-auto pr-4 flex">
                            <a href="{{ route('scholars.profile.publication.edit', $publication) }}" 
                                class="p-1 text-gray-500 text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                                <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                            </a>
                            <form method="POST" action="{{ route('scholars.profile.publication.destroy', $publication->id) }}"
                                onsubmit="return confirm('Do you really want to delete this publication?');">
                                @csrf_token
                                @method('DELETE')
                                <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                                    <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul class="border rounded-lg overflow-hidden mb-4">
    </div>
</div>
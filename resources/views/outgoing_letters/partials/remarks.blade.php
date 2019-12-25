<div class="bg-gray-100 justify-between overflow-y-auto">
    @can('edit remarks')
    <remark-update-modal name="remark-update-modal">@csrf_token @method('patch')</remark-update-modal>
    @endcan
    <div class="border-b px-6 py-2">
        @can('create', App\Remark::class)
        <form action="{{ route('outgoing_letters.remarks.store', $letter) }}" method="POST">
            @csrf_token
            <div class="flex items-center">
            <img src="https://gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?s=48&d=identicon"
                alt="{{ Auth::user()->name }}"
                width="32" height="32"
                class="w-8 h-8 rounded-full mr-4">
                <input name="description" rows="1"
                    placeholder="Give remarks here..."
                    class="flex-1 form-input mr-4 bg-white">
                <button class="btn btn-magenta">Add Remark</button>
            </div>
        </form>
        @else
        <p class="text-center text-gray-600">You are not allowed to add remarks.</p>
        @endcan
    </div>
    @forelse($letter->remarks as $i => $remark)
    <div class="relative hover:bg-gray-200 px-6 py-2 mb-2 last:mb-0">
        <div class="flex items-center mb-2">
            <img src="https://gravatar.com/avatar/{{ md5(strtolower(trim($remark->user->email))) }}?s=48&d=identicon"
                alt="{{ $remark->user->name }}" width="32" height="32"
                class="w-8 h-8 rounded-full mr-4">
            <div>
                <h4 class="text-gray-900 font-bold text-lg leading-none">{{ $remark->user->name }}</h4>
                <span class="text-xs text-gray-700 font-bold">
                    {{ $remark->updated_at->format('M d, Y h:i a') }}
                </span>
            </div>
        </div>
        <p class="pl-12 text-gray-800 mr-10">{{ $remark->description }}</p>
        <div class="absolute right-0 top-0 mr-4 flex items-center">
            @can('update', $remark)
            <button class="p-1 text-gray-500 hover:bg-gray-200 text-blue-600 rounded mr-3"
                title="Edit"
                @click.prevent="$modal.show('remark-update-modal',{
                    remark: {{ $remark->toJson() }}
                })">
                <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
            </button>
            @endcan
            @can('delete', $remark)
            <form action="{{ route('remarks.destroy', $remark) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete remark?');">
                @csrf_token @method('DELETE')
                <button class="p-1 hover:bg-gray-200 text-red-700 rounded">
                    <feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</feather-icon>
                </button>
            </form>
            @endcan
        </div>
    </div>
    @empty
    <div class="py-3 px-6">
        <p class="text-gray-600">No Remarks added yet.</p>
    </div>
    @endforelse
</div>

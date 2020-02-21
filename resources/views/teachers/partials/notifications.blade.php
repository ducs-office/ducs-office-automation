<toggle-visibility class="ml-2 relative">
    <template v-slot="{ isVisible, toggle }">
        <button class="p-1 text-blue-500" @click="toggle">
            @php
                $notificationCount = Auth::user()->unreadNotifications->count();
            @endphp
            <p class="h-current -mr-3 -mb-1 font-bold text-red-500" v-if="{{ $notificationCount }}">
                {{ $notificationCount }}
            </p>
            <feather-icon name="bell" class="h-6">Notifications</feather-icon>
        </button>
        <transition enter-active-class="transition" leave-active-class="transition"
            enter-class="translate-x-200 opacity-0" leave-to-class="translate-x-200 opacity-0">
            <div v-if="isVisible" class="absolute right-0 mt-2 bg-white w-72 max-h-screen-1/2 overflow-y-auto z-10 border rounded shadow-lg">
                <h5 class="px-4 font-bold mt-4 mb-2 shadow-sm">Notifications</h5>
                @forelse (Auth::user()->notifications as $notification)
                    <p class="px-4 py-2 border-t{{ $notification->read_at ? ' bg-gray-100 ' : ' bg-white font-bold ' }}hover:bg-gray-200">
                        {{ $notification->data['inform'] }}
                    </p>
                    @php
                        $notification->markAsRead();
                    @endphp
                @empty
                    <p class="text-gray-500 font-bold text-center">No notifications.</p>
                @endforelse
            </div>
        </transition>
    </template>
<toggle-visibility>

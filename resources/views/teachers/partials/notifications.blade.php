<toggle-visibility class="ml-2">
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
            <div v-if="isVisible" class="absolute max-h-screen-1/2 bg-gray-300 z-10 p-4 border-b rounded shadow-lg">
                <h5 class="border-b p-1 font-bold mb-2 shadow-sm">Notifications </h5>
                @foreach (Auth::user()->unReadNotifications as $unReadNotification)
                    <p class="mb-2 p-1 border bg-blue-200 text-gray-700 font-semibold">
                        {{ $unReadNotification->data['inform'] }}
                    </p>
                    @php
                        $unReadNotification->markAsRead();
                    @endphp
                @endforeach
            </div>
        </transition>
    </template>
<toggle-visibility>
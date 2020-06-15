<div class="max-w-full overflow-x-auto">
    <table class="w-full border-collapse text-left">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="pl-6 pr-4 py-2 border-b table-fit">Resource</th>
                <th class="pl-4 pr-6 py-2 border-b">Actions</th>
            </tr>
        </thead>
        @foreach ($permissions as $group => $gPermissions)
        <tr class="hover:bg-gray-200 text-left">
            <th class="pl-6 pr-4 py-2 border-b table-fit">{{ $group }}:</th>
            <td class="pr-6 pl-4 py-2 border-b">
                <div class="flex space-x-2">
                    @foreach ($gPermissions as $permission)
                    <div class="px-2 py-1 rounded inline-flex items-center space-x-2
                        @if($role->permissions->contains($permission->id))
                            bg-magenta-700 text-white @else bg-gray-300 text-gray-800
                        @endif">
                        @if($role->permissions->contains($permission->id))
                        <x-feather-icon name="check" class="h-current"></x-feather-icon>
                        @else
                        <x-feather-icon name="x" class="h-current"></x-feather-icon>
                        @endif
                        <span>{{ explode(':', $permission->name, 2)[1] }}</span>
                    </div>
                    @endforeach
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>

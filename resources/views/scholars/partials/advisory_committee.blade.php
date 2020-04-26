<div class="my-3 flex-1">
    <ul class="border rounded-lg overflow-hidden mb-4">
        @foreach ($advisoryCommittee as $member)
        <li class="px-4 py-3 border-b last:border-b-0">
            <h3>{{ $member->name }}</h3>
        </li>
        @endforeach
    </ul>
</div>

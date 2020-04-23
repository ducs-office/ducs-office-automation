<div class="my-3 flex-1">
    <ul class="border rounded-lg overflow-hidden mb-4">
        <li class="px-4 py-3 border-b last:border-b-0">
            <h3>Supervisor: {{$advisoryCommittee['supervisor']}}</h3>
        </li>
        @if(array_key_exists('cosupervisor', $advisoryCommittee))
        <li class="px-4 py-3 border-b last:border-b-0">
            <h3>Cosupervisor: {{$advisoryCommittee['cosupervisor']}}</h3>
        </li>
        @endif
        @if (array_key_exists('faculty_teacher', $advisoryCommittee))
        <li class="px-4 py-3 border-b last:border-b-0">
            <h3>Faculty Teacher: {{$advisoryCommittee['faculty_teacher']}}, DUCS</h3>
        </li>
        @endif
        @if (array_key_exists('external', $advisoryCommittee))
        <li class="px-4 py-3 border-b last:border-b-0">
            <h3 class="mb-1">
                External: 
                {{$advisoryCommittee['external']['name']}}, {{$advisoryCommittee['external']['designation']}}, {{$advisoryCommittee['external']['affiliation']}}
            </h3>
            <div class="flex items-center">
                <feather-icon name="at-sign" class="text-gray-800 h-3 mr-2"></feather-icon>
                <h4 class="text-gray-800">
                    {{$advisoryCommittee['external']['email']}}
                </h4>
            </div>
            @if (array_key_exists('phone_no', $advisoryCommittee['external']))
                <div class="flex items-center">
                    <feather-icon name="phone" class="text-gray-800 h-3 mr-2"></feather-icon>
                    <h4 class="text-gray-800">
                        {{$advisoryCommittee['external']['phone_no']}}
                    </h4>
                </div>
            @endif
        </li>
        @endif
    </ul>
</div>
<div class="space-y-2">
    <div class="text-right">
        <button type="button" class="link" wire:click="add()">add more...</button>
    </div>
    <div>
        <table class="w-auto">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-500 bg-gray-100 text-left text-sm leading-4 text-gray-700 uppercase">
                        Institute
                    </th>
                    <th class="px-6 py-3 border-b border-gray-500 bg-gray-100 text-left text-sm leading-4 text-gray-700 uppercase">
                        Degree
                    </th>
                    <th class="px-6 py-3 border-b border-gray-500 bg-gray-100 text-left text-sm leading-4 text-gray-700 uppercase">
                        Subject
                    </th>
                    <th class="px-6 py-3 border-b border-gray-500 bg-gray-100 text-left text-sm leading-4 text-gray-700 uppercase">
                        Year
                    </th>
                    <th class="px-6 py-3 border-b border-gray-500 bg-gray-100 text-left text-sm leading-4 text-gray-700 uppercase"></th>
                </tr>
                <tbody x-data="{count: {{ count($educationItems) }} }">
                    @foreach ($educationItems as $index => $educationItem)
                        <tr>
                            <td class="px-6 py-4">
                                <x-select-with-other
                                    class="w-64"
                                    select-class="w-full form-select"
                                    input-class="w-full form-input"
                                    name="education_details[{{$index}}][institute]"
                                    input-name="education_details[{{$index}}][institute]"
                                    value="{{ $educationItem['institute']}}"
                                    >

                                    @foreach($data['institutes'] as $institute)
                                    <option value="{{ $institute['name'] }}">{{ $institute['name'] }}</option>
                                    @endforeach

                                </x-select-with-other>
                            </td>
                            <td class="px-6 py-4 ">
                                <x-select-with-other
                                    class="w-64"
                                    select-class="w-full form-select"
                                    input-class="w-full form-input"
                                    name="education_details[{{$index}}][degree]"
                                    input-name="education_details[{{$index}}][degree]"
                                    value="{{ $educationItem['degree']}}"
                                    >
                                    @foreach($data['degrees'] as $degree)
                                    <option value="{{ $degree['name'] }}">{{ $degree['name'] }}</option>
                                    @endforeach

                                </x-select-with-other>
                            </td>
                            <td class="px-6 py-4">
                                <x-select-with-other
                                    class="w-64"
                                    select-class="w-full form-select"
                                    input-class="w-full form-input"
                                    name="education_details[{{$index}}][subject]"
                                    input-name="education_details[{{$index}}][subject]"
                                    value="{{ $educationItem['subject'] }}"
                                    >

                                    @foreach($data['subjects'] as $subject)
                                    <option value="{{ $subject['name'] }}">{{ $subject['name'] }}</option>
                                    @endforeach

                                </x-select-with-other>
                            </td>
                            <td class="px-6 py-4 ">
                                <input type="text"
                                    name="education_details[{{$index}}][year]"
                                    x-model="'{{$educationItem['year'] ?? ''}}'"
                                    class="w-full form-input">
                            </td>
                            <td class="px-6 py-4" x-show="count > 1">
                                <button type="button" class="p-2 group" wire:click="remove({{ $index }})">
                                    <x-feather-icon name="x" class="h-6 transform transition duration-150 group-hover:scale-110"></x-feather-icon>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </thead>
        </table>
    </div>
</div>

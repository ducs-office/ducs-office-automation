<form action="{{ route('scholars.courseworks.store', $scholar) }}"
    method="POST" class="flex">
    @csrf_token
    <select id="course_id" name="course_id"
        class="w-full form-select rounded-r-none"
        @error('course_id', 'addCoursework') border-red-500 hover:border-red-700 @enderror">
        @foreach ($courses as $course)
            <option value="{{$course->id}}">
                [{{ $course->code }}] {{ $course->name }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none">Add</button>
    @error('course_id', 'addCoursework')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
</form>

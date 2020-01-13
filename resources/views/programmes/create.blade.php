@extends('layouts.master')
@section('body')
<div class="page-card max-w-xl my-4 mx-auto">
    <h2 class="page-header px-6">New Programme</h2>
    <form action="{{ route('programmes.store') }}" method="POST" class="px-6">
        @csrf_token
        <div class="mb-2">
            <label for="programme_code" class="w-full form-label">Code<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="programme_code" type="text" name="code" class="w-full form-input">
        </div>
        <div class="mb-2">
            <label for="programme_wef" class="w-full form-label">Date (w.e.f)<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="programme_wef" type="date" name="wef" class="w-full form-input">
        </div>
        <div class="mb-2">
            <label for="programme_name" class="w-full form-label">Name<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="programme_name" type="text" name="name" class="w-full form-input">
        </div>
        <div class="mb-2">
            <label for="programme_type" class="w-full form-label">Type<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <select class="w-full form-input" name="type" required>
                <option value="Under Graduate(U.G.)"
                    {{ old('type', 'Under Graduate(U.G.)') === 'Under Graduate(U.G.)' ? 'selected' : ''}}>Under
                    Graduate(U.G.)</option>
                <option value="Post Graduate(P.G.)"
                    {{ old('type', 'Post Graduate(P.G.)') === 'Post Graduate(P.G.)' ? 'selected' : ''}}>Post
                    Graduate(P.G.)</option>
            </select>
        </div>
        <div class="mb-2">
            <course-sections></course-sections>
        </div>
        <div class="mb-2">
            <button type="submit" class="btn btn-magenta">Create</button>
        </div>
    </form>
</div>
@endsection



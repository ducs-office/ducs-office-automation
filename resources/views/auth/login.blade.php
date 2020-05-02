@extends('layouts.guest')
@section('body')
<div class="page-card mb-6 sm:mb-12 max-w-lg mx-auto">
    <h3 class="page-header">Login</h3>
    <form action="{{ route('login') }}" method="POST" class="px-6">
        @csrf_token
        <fieldset class="mb-2 border p-2 rounded">
            <legend class="form-label px-1">Login as</legend>
            <div class="flex flex-wrap -mx-1 -my-1">
                <label class="inline-flex items-center mx-3 my-1">
                    <input type="radio" name="type" id="web"
                        value="web" class="form-radio"
                        {{ old('type', 'web') === 'web' ? 'checked' : '' }}>
                    <span for="web" class="form-label ml-2">Department</span>
                </label>
                <label class="inline-flex items-center mx-3 my-1">
                    <input type="radio" name="type" id="teachers"
                        value="teachers" class="form-radio"
                        {{ old('type', 'web') === 'teachers' ? 'checked' : '' }}>
                    <span for="teachers" class="form-label ml-2">College Teacher</span>
                </label>
                <label class="inline-flex items-center mx-3 my-1">
                    <input type="radio" name="type" id="scholars"
                        value="scholars" class="form-radio"
                        {{ old('type', 'web') === 'scholars' ? 'checked' : ''}}>
                    <span for="scholars" class="form-label ml-2">PhD Scholar</span>
                </label>
            </div>
            @if($errors->has('type'))
                <p class="text-red-600 mt-1">{{ $errors->first('type') }}</p>
            @endif
        </fieldset>
        <div class="mb-2">
            <label class="w-full form-label mb-1" for="email">Email</label>
            <input type="email"
                name="email"
                class="w-full form-input{{ $errors->has('email') ? ' border-red-600' : '' }}"
                placeholder="e.g. johndoe@example.com"
                value="{{ old('email') }}"
                required>
            @if($errors->has('email'))
                <p class="text-red-600 mt-1">{{ $errors->first('email') }}</p>
            @endif
        </div>
        <div class="mb-2">
            <label class="w-full form-label mb-1" for="password">Password</label>
            <input type="password" name="password" class="w-full form-input" placeholder="Enter your password here..." required>
        </div>
        <div class="mb-5">
            <label for="remember" class="flex items-center">
                <input type="checkbox" name="remember"
                id="remember" class="form-checkbox"
                {{ old('remember', false) ? 'checked' : ''}}>
                <span class="form-label ml-2">Remember me</span>
            </label>
        </div>
        <div>
            <button type="submit" class="w-full btn btn-magenta py-2">Login</button>
        </div>
    </form>
</div>
@endsection

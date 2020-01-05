@extends('layouts.guest')
@section('body')
<div class="my-6 sm:my-12 max-w-lg mx-auto page-card">
    <h3 class="page-header">Login</h3>
    <form action="{{ route('login') }}" method="POST" class="px-6">
        @csrf_token
        <div class="mb-2">
            <label class="w-full form-label mb-1" for="email">Email</label>
            <input type="text"
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
        <div class="mb-4">
            <label for="remember" class="flex items-center cursor-pointer font-semibold text-gray-600">
                <input type="checkbox" name="remember" id="remember" class="mr-1" class="form-checkbox text-magenta-600" {{old('remember', false) ? 'checked' : ''}}>
                Remember me
            </label>
        </div>
        <div>
            <button type="submit" class="w-full btn btn-magenta py-2">Login</button>
        </div>
    </form>
</div>
@endsection

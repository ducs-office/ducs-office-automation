@extends('layouts.guest')
@section('body')
<form action="{{ route('login') }}" method="POST" class="mt-6 sm:mt-16 max-w-lg mx-auto page-card px-6">
    @csrf_token
    <h3 class="mb-6 text-2xl font-semibold">Login</h3>
    <div class="mb-3">
        <label class="w-full form-label mb-1" for="email">Email</label>
        <input type="text" name="email" class="w-full form-input" placeholder="e.g. johndoe@example.com">
    </div>
    <div class="mb-2">
        <label class="w-full form-label mb-1" for="password">Password</label>
        <input type="password" name="password" class="w-full form-input" placeholder="Enter your password here...">
    </div>
    <div class="mt-4 mb-2 flex flex-row justify-between">
        <label for="remember" class="flex items-center cursor-pointer font-semibold text-gray-600">
            <input type="checkbox" name="remember" id="remember" class="mr-1" class="form-checkbox">
            Remember me
        </label>
        <a href="/reset-password" class="text-sm link">Forgot your password?</a>
    </div>
    <div>
        <button type="submit" class="w-full btn btn-magenta py-2 mb-2">Login</button>
    </div>
</form>
@endsection

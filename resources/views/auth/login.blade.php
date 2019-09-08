@extends('layouts.guest')
@section('body')
<form action="/login" method="POST" class="p-4 md:p-6 bg-white max-w-md mx-auto rounded-lg shadow-md border">
    {{ csrf_field() }}
    <h3 class="mb-6 text-2xl font-semibold">Login</h3>
    <div class="mb-3">
        <label class="block w-full text-xs font-semibold tracking-wider uppercase text-gray-700 mb-1" for="email">Email</label>
        <input type="text" name="email" class="w-full border border-gray-700 px-3 py-2 rounded hover:border-gray-900 focus:border-gray-900" placeholder="e.g. johndoe@example.com">
    </div>
    <div class="mb-2">
        <label class="block w-full text-xs font-semibold tracking-wider uppercase text-gray-700 mb-1" for="password">Password</label>
        <input type="password" name="password" class="w-full border border-gray-700 px-3 py-2 rounded hover:border-gray-900 focus:border-gray-900" placeholder="Enter your password here...">
    </div>
    <div class="text-sm text-gray-600 mb-3">
        <label for="remember" class="flex items-center cursor-pointer">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            Remember me
        </label>
    </div>
    <div class="mt-6">
        <button type="submit" class="w-full bg-gray-900 text-white px-3 py-2 font-bold rounded mb-1">Login</button>
        <a href="/reset-password" class="text-sm text-gray-600 hover:underline">Forgot your password?</a>
    </div>
</form>
`
@endsection

@extends('layouts.guest')
@section('body')
<div class="page-card mb-6 sm:mb-12 max-w-lg mx-auto">
    <h3 class="page-header">
        Forgot Password
        @if(request()->has('scholar'))
        <small class="text-sm text-gray-600">(Scholar only)</small>
        @endif
    </h3>
    <form action="{{ route('password.send', request()->has('scholar') ? ['scholar'] : []) }}" method="POST" class="px-6 space-y-3">
        @csrf_token
        <p>A password reset link will be sent to your registered email.</p>
        <div>
            <label class="w-full form-label mb-1" for="email">Registered Email</label>
            <input type="email" name="email" class="w-full form-input{{ $errors->has('email') ? ' border-red-600' : '' }}"
                placeholder="e.g. johndoe@example.com" value="{{ old('email') }}" required>
            @if($errors->has('email'))
            <p class="text-red-600 mt-1">{{ $errors->first('email') }}</p>
            @endif
        </div>
        <div class="mt-6">
            <button type="submit" class="w-full btn btn-magenta py-2">Send Password Reset Link</button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.guest')
@section('body')
<div class="page-card mb-6 sm:mb-12 max-w-lg mx-auto">
    <h3 class="page-header">Reset Password
        @if(request()->has('scholar'))
        <small class="text-sm text-gray-600">(Scholar only)</small>
        @endif
    </h3>
    <form action="{{ route('password.update', request()->has('scholar') ? ['scholar'] : []) }}"
        method="POST" class="px-6 space-y-3">
        @csrf_token
        <input type="hidden" name="token" value="{{ $token }}">
        @error('token')
        <div class="text-red-600" role="alert">
            <p>This URL expired. Request again</p>
        </div>
        @enderror

        <div>
            <label for="email" class="w-full form-label mb-1">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="w-full form-input" name="email"
                value="{{ $email ?? old('email') }}"
                required autocomplete="email" autofocus>
            @error('email')
            <span class="text-red-600" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div>
            <label for="password" class="w-full form-label mb-1">{{ __('Password') }}</label>
            <x-input.password id="password" class="w-full form-input" name="password" required></x-input.password>
            @error('password')
            <span class="text-red-600" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div>
            <label for="password-confirm" class="w-full form-label mb-1">{{ __('Confirm Password') }}</label>
            <input type="password" id="password-confirm" class="w-full form-input" name="password_confirmation" required>
        </div>
        <div class="mt-6">
            <button type="submit" class="w-full btn btn-magenta py-2">Reset Password</button>
        </div>
    </form>
</div>
@endsection

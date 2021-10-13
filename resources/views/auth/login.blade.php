<!--<link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@extends('layouts.guest')
@section('body')
    <form action="{{ route('login', request()->has('scholar') ? ['scholar'] : []) }}" method="POST"
        class="max-w-lg mx-auto">
        @csrf_token
        <div class="page-card p-6 mb-4 space-y-3">
            @if(! request()->has('scholar'))
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold">Login</h3>
                <a href="{{ route('login-form', ['scholar']) }}" class="link">Login as Scholar?</a>
            </div>
            @else
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold">Login <small class="text-sm text-gray-600">(Scholar only)</small></h3>
                <a href="{{ route('login-form') }}" class="link">Not a Scholar?</a>
            </div>
            @endif
            <div>
                <label class="w-full form-label mb-1" for="email">Email</label>
                <input type="email" name="email" class="w-full form-input{{ $errors->has('email') ? ' border-red-600' : '' }}"
                    placeholder="e.g. johndoe@example.com" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                <p class="text-red-600 mt-1">{{ $errors->first('email') }}</p>
                @endif
            </div>
            <div>
                <label class="w-full form-label mb-1" for="password">Password</label>
                <x-input.password name="password" class="w-full form-input" placeholder="Enter your password here..." required></x-input.password>
            </div>

            <div>
                <div class="captcha  flex justify-between items-center mb-2">
                    
                    <div class="w-2.5/6  py-2 ">
                        <span >{!! captcha_img() !!}</span>
                    </div>
                    <div class="w-0.5/6  text-xl text-left ">
                       <button type="button" id="reload" class="reload form-input px-3 py-2 " > &#x21bb;</button>    
                    </div>
                    <div class="w-2.5/6">
                        <input type="text" id="captcha" name="captcha"  class="w-full form-input " placeholder="Enter Captcha"  autocomplete="off">
                    </div>
                </div>
            </div>
           <!--<div>
                <input type="text" id="captcha" name="captcha"  class="w-full form-input mb-3" placeholder="Enter Captcha"  autocomplete="off">
            </div>-->
            <div>
                <label for="remember" class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="form-checkbox"
                        {{ old('remember', false) ? 'checked' : ''}}>
                    <span class="form-label ml-2">Remember me</span>
                </label>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta py-2">Login</button>
            </div>
        </div>
        <div class="px-4 mb-6 sm:mb-10 md:mb-12">
            @if(request()->has('scholar'))
            <a class="link font-bold text-white-70 hover:text-white" href="{{ route('password.forgot', ['scholar']) }}">Forgot password?</a>
            @else
            <a class="link font-bold text-white-70 hover:text-white" href="{{ route('password.forgot') }}">Forgot password?</a>
            @endif
        </div>
    </form>

    <script type="text/javascript">
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });

</script>
@endsection

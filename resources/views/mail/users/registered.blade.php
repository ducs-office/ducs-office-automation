@component('mail::message')

**Hello {{ $user->name }}!**

You have been registered with us. Please reset your password using the link below, to get started.

@component('mail::button', [
    'url' => route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ])
]) Reset Password & Login @endcomponent

The given link will expire in {{ config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') }} minutes.
You can request a new reset password link [here]({{ route('password.forgot', ['email' => $user->email]) }})


<p style="font-size: 12px; text-align: center; padding-top: 20px; color: #aaa;">
If you did not expect yourself registered, you can ignore this mail.
</p>
@endcomponent

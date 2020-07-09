@component('mail::message')

**Hello {{ $scholar->name }}!**

You have been registered with us as a _Phd Scholar_, under the supervison of **{{ $supervisor->name }}** {{ $cosupervisor ? "and **$cosupervisor->name**" : '' }}.
Please fill up your profile as soon as possible, you can reset your password using the link below, to get started.

@component('mail::button', [
    'url' => route('password.reset', [
        'token' => $token,
        'email' => $scholar->email,
        'scholar'
    ])
]) Reset Password & Login @endcomponent

The given link will expire in {{ config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') }} minutes.
You can request a new password reset link [here]({{ route('password.forgot', ['email' => $scholar->email, 'scholar']) }}).


<p style="font-size: 12px; text-align: center; padding-top: 20px; color: #aaa;">
If you did not expect yourself registered, you can ignore this mail.
</p>
@endcomponent

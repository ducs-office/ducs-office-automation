@component('mail::message')
    ### Welcome

    Hi there! you have been registered on DUCS Office Portal. Below are your login credentials.
    Please change your password immediately after first login.
    <p><b>Email:</b> {{ $email }}</p>
    <p><b>Password:</b> {{ $password }}</p>
@endcomponent

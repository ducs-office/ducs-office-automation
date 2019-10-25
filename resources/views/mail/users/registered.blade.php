<h1>
    Welcome to DUCS
</h1>

<p>
    Hi {{ $user->name }}! you have been registered on DUCS Office Portal. Below are your login credentials.
    Please change your password immediately after first login.
</p>
    
<p>
    <b>Email:</b> {{ $user->email }} <br>
    <b>Password:</b> {{ $password }}
</p>

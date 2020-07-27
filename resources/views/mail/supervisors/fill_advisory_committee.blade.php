@component('mail::message')

**Hello <b>{{ $supervisor->name }}</b>!**

A new scholar, <b>{{ $scholarName }}</b>, has been registered under your supervision.
Please fill the scholar's advisory committee by <b>{{ $deadline }}</b>.

@component('mail::button', [
    'url' => env('APP_URL', 'http://office.cs.du.ac.in')
]) Visit Portal @endcomponent

@endcomponent

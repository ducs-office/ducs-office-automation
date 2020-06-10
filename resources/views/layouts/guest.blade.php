<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="font-sans leading-tight bg-magenta-800">
    <div class="min-h-screen px-4 space-y-8">
        <header class="max-w-lg mx-auto">
            <a href="/" class="flex items-center py-8 px-4">
                <img src="{{ asset('images/university-logo.png') }}" alt="University of Delhi - Logo" class="w-16 sm:w-20 flex-shrink-0 mr-3">
                <div class="flex-1 text-white leading-none">
                    <h2 class="text-lg sm:text-2xl mb-1 sm:mb-2 text-">Department of Computer Science</h2>
                    <h4 class="text-base sm:text-lg text-white-80">University of Delhi</h4>
                </div>
            </a>
        </header>
        <main>
            @yield('body')
        </main>
        @include('flash::message')
    </div>
    @stack('scripts')
</body>
</html>

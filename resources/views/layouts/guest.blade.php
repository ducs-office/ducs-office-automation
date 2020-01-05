<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @routes
</head>
<body class="font-sans leading-tight bg-magenta-800 text-sm">
    <div id="app" class="min-h-screen px-4">
        <header class="flex items-center w-full max-w-lg mx-auto py-8 px-4">
            <img src="{{ asset('images/university-logo.png') }}" alt="University of Delhi - Logo" class="w-16 flex-shrink mr-3" style="min-width: 100px;">
            <div class="flex-grow text-white leading-none">
                <h2 class="text-2xl mb-2">Department of Computer Science</h2>
                <h4 class="text-lg font-bold">University of Delhi</h4>
            </div>
        </header>
        <main>
            @yield('body')
        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

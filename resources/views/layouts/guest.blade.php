<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body class="font-sans leading-tight bg-gray-200 text-sm">
    <div id="app" class="min-h-screen flex flex-col justify-center px-4">
        <header class="w-full max-w-md mx-auto text-center mb-8">
            <h2 class="text-xl font-semibold">Department of Computer Science</h2>
            <h4 class="text-sm tracking-wider uppercase">university of Delhi</h4>
        </header>
        <main>
            @yield('body')
        </main>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
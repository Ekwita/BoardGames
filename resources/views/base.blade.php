<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>

<body id= "app" class="bg-gray-100">
    <header>
        <nav class="bg-gray-800">
            <div class="container mx-auto px-4 flex items-center justify-between h-20"> <!-- Wyższy pasek menu -->
                <div class="text-gray-100 flex justify-start">
                    @yield('nav') <!-- Menu -->
                </div>
            </div>
        </nav>
    </header>
    <div>
        @yield('title')
    </div>
    <div class="container mx-auto px-4">
        @yield('content')
    </div>
    <footer class="fixed bottom-0 min-w-full bg-black">
        @yield('footer')
    </footer>
</body>

</html>

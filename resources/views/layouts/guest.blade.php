<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cmyk">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Chansey Nursing Informatics System</title>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans min-h-screen flex items-center justify-center bg-sky-200">


    <div class="card w-full max-w-md bg-base-100 shadow-2xl">
        <div class="card-body flex flex-col items-center text-center">
            <div class="avatar mb-4">
                <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                    <img src="{{ asset('images/chansey.jpg') }}" alt="Chansey Logo">
                </div>
            </div>
            <h1 class="card-title text-2xl font-bold text-primary">Chansey Nursing Informatics</h1>
            <p class="text-sm text-base-content/60">Hospital Training & Clinical Informatics System</p>
        </div>

        <main role="main" aria-labelledby="login-heading" class="card-body pt-0">
            {{ $slot }}
        </main>

        <footer class="card-footer flex justify-center text-center text-xs text-base-content/40 border-t border-base-300 py-3">
            &copy; {{ date('Y') }} Chansey Hospital — For authorized staff only
        </footer>
    </div>


</body>

</html>
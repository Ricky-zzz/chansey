<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="emerald">

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

<body class="font-sans min-h-screen flex items-center justify-center bg-base-200">


    <div class="card w-full max-w-md bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="avatar mb-4">
                    <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="{{ asset('images/chansey.jpg') }}" alt="Chansey Logo">
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-primary">Chansey Nursing Informatics</h1>
                <p class="text-sm opacity-70">Hospital Training & Clinical Informatics System</p>
            </div>

            <main>
                {{ $slot }}
            </main>

            <div class="divider"></div> 

            <footer class="text-center text-xs opacity-50">
                &copy; {{ date('Y') }} Chansey Hospital — For authorized staff only
            </footer>
        </div>
    </div>


</body>

</html>
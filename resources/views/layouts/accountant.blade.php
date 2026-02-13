<!DOCTYPE html>
<html lang="en" data-theme="winter">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Golden Gate College - Accounting Console</title>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">
    @if (session('success'))
    <div class="toast toast-top toast-end z-50" x-data="{ show: true }" x-show="show">
        <div class="toast-enterprise-success" @click.outside="show = false">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-emerald-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <span class="font-medium">Success!</span> {{ session('success') }}
            </div>
            <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="toast toast-top toast-end z-50" x-data="{ show: true }" x-show="show">
        <div class="toast-enterprise-error" @click.outside="show = false">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m8-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <span class="font-medium">Error!</span> {{ session('error') }}
            </div>
            <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    @endif

    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

        <!-- MAIN CONTENT AREA -->
        <div class="drawer-content flex flex-col bg-slate-50">
            <!-- Mobile Navbar -->
            <div class="w-full navbar bg-white border-b border-slate-200 lg:hidden z-10">
                <div class="flex-none">
                    <label for="my-drawer-2" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </label>
                </div>
                <div class="flex-1 px-2 mx-2 font-bold text-lg text-slate-800 tracking-tight">Golden Gate College</div>
            </div>

            <!-- Content Injection -->
            <main class="p-6 lg:p-8 max-w-7xl mx-auto w-full">
                @yield('content')
            </main>
        </div>

        <!-- SIDEBAR -->
        <x-accntsidebar />
    </div>
</body>

</html>

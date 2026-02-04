<!DOCTYPE html>
<html lang="en" data-theme="emerald">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chansey Hospital - Trusted Care, Anytime, Anywhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50">
    @if(session('success'))
    <div id="successAlert" class="alert alert-success text-sm fixed bottom-6 right-6 w-96 shadow-lg z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
        <button onclick="document.getElementById('successAlert').remove()" class="btn btn-sm btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-4 h-4 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif
    <!-- HEADER -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                        C
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800">Chansey Hospital</h1>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#about" class="text-slate-600 hover:text-emerald-600 font-medium transition">About</a>
                    <a href="#services" class="text-slate-600 hover:text-emerald-600 font-medium transition">Services</a>
                    <a href="#appointment" class="text-slate-600 hover:text-emerald-600 font-medium transition">Book Now</a>
                    <a class="btn bg-amber-500 hover:bg-amber-400 text-white btn-sm gap-2 border-2 border-amber-600 hover:scale-105 transition-transform" href="{{ route('login') }}">Staff Login</a>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="md:hidden dropdown dropdown-end">
                    <button class="btn btn-ghost btn-circle btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <ul class="dropdown-content z-1 menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a href="#about">About</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#appointment">Book Now</a></li>
                        <li><a class="btn bg-amber-500 hover:bg-amber-400 text-white btn-sm gap-2 border-2 border-amber-600 hover:scale-105 transition-transform" href="{{ route('login') }}">Staff Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="space-y-6">
                <div class="space-y-3">
                    <span class="inline-block px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">Welcome to Chansey Hospital</span>
                    <h2 class="text-5xl lg:text-6xl font-bold text-slate-900 leading-tight">
                        Your Health Is Our Priority
                    </h2>
                    <p class="text-xl text-slate-600">
                        Experience compassionate, high-quality healthcare with convenient online appointment booking.
                    </p>
                </div>

                <!-- Features -->
                <div class="space-y-4 pt-4">
                    <div class="flex gap-4 items-start">
                        <div class="shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-emerald-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">24/7 Availability</h3>
                            <p class="text-slate-600 text-sm">Book appointments anytime, day or night</p>
                        </div>
                    </div>

                    <div class="flex gap-4 items-start">
                        <div class="shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-emerald-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Expert Healthcare Professionals</h3>
                            <p class="text-slate-600 text-sm">Experienced doctors and specialists at your service</p>
                        </div>
                    </div>

                    <div class="flex gap-4 items-start">
                        <div class="shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-emerald-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">State-of-the-Art Facilities</h3>
                            <p class="text-slate-600 text-sm">Modern equipment and comfortable environments</p>
                        </div>
                    </div>
                </div>

                <a href="#appointment" class="inline-block btn btn-md btn-primary mt-4 text-xl pt-2">Book Appointment Now</a>
            </div>

            <!-- Right Visual -->
            <div class="hidden lg:block">
                <div class="bg-gradient-to-br from-emerald-400 to-teal-500 rounded-3xl h-96 flex items-center justify-center shadow-2xl">
                    <div class="text-center text-white">
                        <svg class="w-32 h-32 mx-auto mb-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold">Healthcare at Your Fingertips</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- APPOINTMENT SECTION -->
    <section id="appointment" class="bg-white py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Book an Appointment</h2>
                <p class="text-lg text-slate-600">Select a department to find available doctors and schedule your visit</p>
            </div>

            <!-- Department Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($departments as $dept)
                <a href="{{ route('public.doctors.index', $dept->id) }}"
                   class="card bg-base-100 shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer border-2 border-transparent hover:border-emerald-500 group">
                    <div class="card-body text-center items-center p-6">
                        <!-- Icon -->
                        <div class="w-16 h-16 rounded-full bg-emerald-100 group-hover:bg-emerald-500 flex items-center justify-center text-3xl mb-3 transition-colors duration-300">
                            <span class="group-hover:scale-110 transition-transform">
                                @switch($dept->name)
                                    @case('Cardiology')
                                        ❤️
                                        @break
                                    @case('Pediatrics')
                                        👶
                                        @break
                                    @case('Orthopedics')
                                        🦴
                                        @break
                                    @case('Neurology')
                                        🧠
                                        @break
                                    @case('Dermatology')
                                        🧴
                                        @break
                                    @case('Ophthalmology')
                                        👁️
                                        @break
                                    @case('ENT')
                                        👂
                                        @break
                                    @case('Gynecology')
                                        🩺
                                        @break
                                    @case('General Medicine')
                                        💊
                                        @break
                                    @case('Surgery')
                                        🏥
                                        @break
                                    @default
                                        🩺
                                @endswitch
                            </span>
                        </div>
                        <h3 class="card-title text-slate-700 text-lg">{{ $dept->name }}</h3>
                        <p class="text-sm text-gray-500">
                            {{ $dept->physicians_count }} {{ Str::plural('Specialist', $dept->physicians_count) }}
                        </p>
                        <div class="mt-2">
                            <span class="text-xs text-emerald-600 font-semibold group-hover:underline">
                                View Doctors →
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            @if($departments->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <p class="text-lg">No departments available at the moment.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="font-semibold text-white mb-4">Chansey Hospital</h3>
                    <p class="text-sm">Providing quality healthcare services to our community since 2024.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-emerald-400 transition">About Us</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition">Services</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-sm">
                        <li>📞 (555) 123-4567</li>
                        <li>📧 contact@chansey.com</li>
                        <li>📍 123 Health Street</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Hours</h3>
                    <ul class="space-y-2 text-sm">
                        <li>Mon-Fri: 8am - 6pm</li>
                        <li>Sat: 9am - 2pm</li>
                        <li>Emergency: 24/7</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-700 pt-8 text-center text-sm">
                <p>&copy; 2024 Chansey Hospital. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>

</html>

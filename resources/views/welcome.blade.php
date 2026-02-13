<!DOCTYPE html>
<html lang="en" data-theme="emerald">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Gate College - Hospital Management System</title>
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
                <div class="flex items-center gap-4 min-h-24 py-2">
                    <div class="w-12 h-12 rounded-full overflow-hidden  flex items-center justify-center bg-white">
                        <a href="{{ route('welcome') }}">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Golden Gate College Logo" class="w-full h-full object-cover rounded-full" />
                        </a>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-800">Golden Gate College</h1>
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
                    <span class="inline-block px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">Welcome to Golden Gate College</span>
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
                                        <!-- Heart Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.676 0-3.154.936-3.937 2.337C11.154 4.686 9.676 3.75 8 3.75 5.41 3.75 3.312 5.765 3.312 8.25c0 7.22 8.188 11.25 8.188 11.25s8.188-4.03 8.188-11.25z" />
                                        </svg>
                                        @break
                                    @case('Pediatrics')
                                        <!-- User Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0v.75a.75.75 0 01-.75.75h-13.5a.75.75 0 01-.75-.75v-.75z" />
                                        </svg>
                                        @break
                                    @case('Orthopedics')
                                        <!-- Bone Icon (Wrench as closest) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-1.5a2.25 2.25 0 11-3.182 3.182l-7.5 7.5a2.25 2.25 0 103.182 3.182l7.5-7.5a2.25 2.25 0 013.182-3.182z" />
                                        </svg>
                                        @break
                                    @case('Neurology')
                                        <!-- Brain Icon (Light Bulb as closest) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a7.5 7.5 0 00-4.5 13.5V19.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5v-3A7.5 7.5 0 0012 3z" />
                                        </svg>
                                        @break
                                    @case('Dermatology')
                                        <!-- Sparkles Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25M12 18.75V21m7.5-9H21m-18 0h2.25m15.364-6.364l-1.591 1.591M4.227 19.773l1.591-1.591m0-12.364l-1.591 1.591m15.364 15.364l-1.591-1.591" />
                                        </svg>
                                        @break
                                    @case('Ophthalmology')
                                        <!-- Eye Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                            <circle cx="12" cy="12" r="3" fill="currentColor" />
                                        </svg>
                                        @break
                                    @case('ENT')
                                        <!-- Ear Icon (Speaker as closest) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 01-9 0V9a4.5 4.5 0 019 0v3z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2.25" />
                                        </svg>
                                        @break
                                    @case('Gynecology')
                                        <!-- Stethoscope Icon (Medical cross as closest) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        @break
                                    @case('General Medicine')
                                        <!-- Capsule/Pill Icon (Beaker as closest) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v2.25m4.5-2.25V5.25m-7.5 4.5h10.5m-10.5 0a2.25 2.25 0 00-2.25 2.25v7.5A2.25 2.25 0 006.75 21h10.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25m-10.5 0V5.25A2.25 2.25 0 018.25 3h7.5A2.25 2.25 0 0118 5.25V9.75" />
                                        </svg>
                                        @break
                                    @case('Surgery')
                                        <!-- Hospital Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21V5a2 2 0 012-2h14a2 2 0 012 2v16" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V9h6v12" />
                                        </svg>
                                        @break
                                    @default
                                        <!-- Medical Icon (Plus) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
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
                    <h3 class="font-semibold text-white mb-4">Golden Gate College</h3>
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
                        <li> (555) 123-4567</li>
                        <li> contact@goldengateCollege.com</li>
                        <li> 123 Health Street</li>
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
                <p>&copy; 2024 Golden Gate College. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>

</html>

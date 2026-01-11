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
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Quick Appointment Booking</h2>
                <p class="text-lg text-slate-600">Fill in your details below and we'll get you scheduled with the right specialist</p>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">

                    <form action="{{ route('public.appointment.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Name Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">First Name</span>
                                </label>
                                <input type="text" name="first_name" placeholder="John" class="input input-bordered" required />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Last Name</span>
                                </label>
                                <input type="text" name="last_name" placeholder="Doe" class="input input-bordered" required />
                            </div>
                        </div>

                        <!-- Contact Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Mobile Number</span>
                                </label>
                                <input type="tel" name="contact_number" placeholder="+1 (555) 000-0000" class="input input-bordered" required />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Email </span>
                                </label>
                                <input type="email" name="email" placeholder="john@example.com" class="input input-bordered required" />
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Department</span>
                            </label>
                            <select name="department_id" class="select select-bordered" required>
                                <option disabled selected>Select a department</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Purpose -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Reason for Visit</span>
                            </label>
                            <textarea name="purpose" class="textarea textarea-bordered h-24" placeholder="Please describe your symptoms or reason for the appointment..." required></textarea>
                        </div>

                        <!-- Preferred Date -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Preferred Appointment Date</span>
                            </label>
                            <input type="date" name="preferred_date" class="input input-bordered" required />
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-full mt-6">Submit Appointment Request</button>

                        <p class="text-center text-sm text-slate-500 mt-4">
                            We'll contact you within 24 hours to confirm your appointment
                        </p>
                    </form>
                </div>
            </div>
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